<?php
if($_POST){
	if(isset($_POST['Btn_Enregistrer']) || isset($_POST['Btn_Verrouiller']) || isset($_POST['Btn_Deverrouiller'])){
		$req="SELECT Id
		FROM moris_moisprestation
		WHERE Id_Prestation=".$_POST['prestation']." 
		AND Annee=".$_POST['annee']." 
		AND Mois=".$_POST['mois']."
		AND Suppr=0 ";
		$result=mysqli_query($bdd,$req);
		$nbResultaMoisPresta=mysqli_num_rows($result);
		
		$fichierSQCDPF="";
		$reqPJ="";

		
		$fichierPRM="";
		$reqPJPRM="";
		//S'il y avait une fichier
		if(isset($_POST['SupprFichierPRM']))
		{
			if($_POST['SupprFichierPRM'])
			{
				if(file_exists ($DirFichierPRM2.$_POST['fichieractuelPRM'])){
					if(unlink($DirFichierPRM2.$_POST['fichieractuelPRM'])){$fichierPRM="";}
				}
				else{
					$fichierPRM="";
				}
				
				$reqPJPRM=",PieceJointePRM='' ";
			}
		}
		
		//****TRANSFERT FICHIER****
		if($_FILES['fichierPRM']['name']!="")
		{
			$tmp_file=$_FILES['fichierPRM']['tmp_name'];
			if(is_uploaded_file($tmp_file)){
				//On vérifie la taille du fichiher
				if(filesize($_FILES['fichierPRM']['tmp_name'])<=$_POST['MAX_FILE_SIZE'])
				{
					// on copie le fichier dans le dossier de destination
					$name_file=$_FILES['fichierPRM']['name'];
					$name_file=strtr($name_file, "@àäâöôéèëêîïùüñç &()[]+*'\\°", "aaaaooeeeeiiuunc___________");
					while(file_exists($DirFichierPRM2.$name_file)){$name_file="_".date('j-m-y')."_".date('H-i-s')." ".$name_file;}
					if(move_uploaded_file($tmp_file,$DirFichierPRM2.$name_file))
					{$fichierPRM=$name_file;$reqPJPRM=",PieceJointeDernierePRM='".$fichierPRM."' ";}
				}
			}
		}
		
		$fichierSatisfactionClient="";
		$reqPJSatisfactionClient="";
		//S'il y avait une fichier
		if(isset($_POST['SupprFichierSatisfactionClient']))
		{
			if($_POST['SupprFichierSatisfactionClient'])
			{
				if(file_exists ($DirFichierSatisfactionClient2.$_POST['fichieractuelSatisfactionClient'])){
					if(unlink($DirFichierSatisfactionClient2.$_POST['fichieractuelSatisfactionClient'])){$fichierSatisfactionClient="";}
				}
				else{
					$fichierSatisfactionClient="";
				}
				
				$reqPJSatisfactionClient=",PieceJointeSatisfactionPRM='' ";
			}
		}
		
		//****TRANSFERT FICHIER****
		if($_FILES['fichierSatisfactionClient']['name']!="")
		{
			$tmp_file=$_FILES['fichierSatisfactionClient']['tmp_name'];
			if(is_uploaded_file($tmp_file)){
				//On vérifie la taille du fichiher
				if(filesize($_FILES['fichierSatisfactionClient']['tmp_name'])<=$_POST['MAX_FILE_SIZE'])
				{
					// on copie le fichier dans le dossier de destination
					$name_file=$_FILES['fichierSatisfactionClient']['name'];
					$name_file=strtr($name_file, "@àäâöôéèëêîïùüñç &()[]+*'\\°", "aaaaooeeeeiiuunc___________");
					while(file_exists($DirFichierSatisfactionClient2.$name_file)){$name_file="_".date('j-m-y')."_".date('H-i-s')." ".$name_file;}
					if(move_uploaded_file($tmp_file,$DirFichierSatisfactionClient2.$name_file))
					{$fichierSatisfactionClient=$name_file;$reqPJSatisfactionClient=",PieceJointeSatisfactionPRM='".$fichierSatisfactionClient."' ";}
				}
			}
		}
		
		$tendance=-1;
		if(isset($_POST['tendanceManagement'])){$tendance=$_POST['tendanceManagement'];}
		
		$PasAT=0;
		if(isset($_POST['PasAT'])){$PasAT=1;}
		$PasNC=0;
		//if(isset($_POST['PasNC'])){$PasNC=1;}
		$PasOTD=0;
		if(isset($_POST['PasOTD'])){$PasOTD=1;}
		$PasOQD=0;
		if(isset($_POST['PasOQD'])){$PasOQD=1;}
		$chargeActive=0;
		if($_POST['ChargeADesactive']==1){$chargeActive=1;}
		$productiviteActive=0;
		if($_POST['ProductiviteADesactive']==1){$productiviteActive=1;}
		$pasActivite=0;
		if(isset($_POST['PasActivite'])==1){$pasActivite=1;}
		if($nbResultaMoisPresta>0){
			$req="UPDATE moris_moisprestation 
				SET RefCDC='".addslashes($_POST['refCDC'])."',
				IntituleCDC='".addslashes($_POST['intituleCDC'])."',
				Id_Contrat=".$_POST['contrat'].",
				Id_Programme=".$_POST['programme'].",
				AcheteurClient='".addslashes($_POST['acheteurClient'])."',
				DonneurOrdre='".addslashes($_POST['donneurOrdre'])."',
				Id_EntiteAchat=".$_POST['entiteAchat'].",
				MailAcheteur='".addslashes($_POST['mailAcheteur'])."',
				MailDO='".addslashes($_POST['mailDO'])."',
				Sigle='".addslashes($_POST['sigle'])."',
				Id_CoorEquipe=".$_POST['coorEquipe'].",
				Id_RespProjet=".$_POST['respProjet'].",
				InterneCurrent=".unNombreSinon0($_POST['interneCurrent']).",
				ChargeDesactive=".$chargeActive.",
				ProductiviteDesactive=".$productiviteActive.",
				PasActivite=".$pasActivite.",
				M1=".unNombreSinon0($_POST['M1']).",
				M2=".unNombreSinon0($_POST['M2']).",
				M3=".unNombreSinon0($_POST['M3']).",
				M4=".unNombreSinon0($_POST['M4']).",
				M5=".unNombreSinon0($_POST['M5']).",
				M6=".unNombreSinon0($_POST['M6']).",
				SubContractorCurrent=".unNombreSinon0($_POST['subContractorCurrent']).",
				BesoinEffectif ='".addslashes($_POST['besoinEffectif'])."',
				TempsAlloue=".unNombreSinon0($_POST['tempsAlloue']).",
				TempsPasse=".unNombreSinon0($_POST['tempsPasse']).",
				TempsObjectif=".unNombreSinon0($_POST['tempsObjectif']).",
				CommentaireProductivite='".addslashes($_POST['commentaireProductivite'])."',
				ObjectifClientOTD=".unNombreSinon0($_POST['objectifClientOTD']).",
				NbLivrableConformeOTD=".unNombreSinon0($_POST['nbLivrableConformeOTD']).",
				NbLivrableToleranceOTD=".unNombreSinon0($_POST['nbLivrableToleranceOTD']).",
				NbRetourClientOTD=".unNombreSinon0($_POST['nbRetourClientOTD']).",
				ObjectifToleranceOTD=".unNombreSinon0($_POST['objectifToleranceOTD']).",
				CauseOTD='".addslashes($_POST['causeOTD'])."',
				ActionOTD='".addslashes($_POST['actionOTD'])."',
				ModeCalculOTD='".addslashes($_POST['modeCalculOTD'])."',
				ObjectifClientOQD=".unNombreSinon0($_POST['objectifClientOQD']).",
				NbLivrableConformeOQD=".unNombreSinon0($_POST['nbLivrableConformeOQD']).",
				NbLivrableToleranceOQD=".unNombreSinon0($_POST['nbLivrableToleranceOQD']).",
				NbRetourClientOQD=".unNombreSinon0($_POST['nbRetourClientOQD']).",
				ObjectifToleranceOQD=".unNombreSinon0($_POST['objectifToleranceOQD']).",
				CauseOQD='".addslashes($_POST['causeOQD'])."',
				ActionOQD='".addslashes($_POST['actionOQD'])."',
				ModeCalculOQD='".addslashes($_POST['modeCalculOQD'])."',
				TendanceManagement='".$tendance."',
				EvenementManagement='".addslashes($_POST['evenementManagement'])."',
				NbXTableauPolyvalence=".unNombreSinon0($_POST['nbXTableauPolyvalence']).",
				NbLTableauPolyvalence=".unNombreSinon0($_POST['nbLTableauPolyvalence']).",
				NbMonoCompetence=".unNombreSinon0($_POST['nbMonoCompetence']).",
				TauxQualif=".unNombreSinon0($_POST['tauxQualif2']).",
				CommentairePlanActionFormation ='".addslashes($_POST['commentairePlanActionFormation'])."',
				DerniereDatePRM='".TrsfDate_($_POST['derniereDatePRM'])."',
				DerniereDateEvaluation='".TrsfDate_($_POST['derniereDateEvaluation'])."',
				DateEnvoiDemandeSatisfaction='".TrsfDate_($_POST['dateEnvoiDemandeSatisfaction'])."',
				PeriodicitePRM='".$_POST['periodicitePRM']."',
				FormatAT=".$_POST['formatSatisfaction'].",
				PasAT='".$PasAT."',
				PasOTD='".$PasOTD."',
				PasOQD='".$PasOQD."',
				PointFortSatisfaction='".addslashes($_POST['pointFort'])."',
				PointFaibleSatisfaction='".addslashes($_POST['pointFaible'])."',
				CommentaireSatisfaction='".addslashes($_POST['CommentaireSatisfaction'])."',
				EvaluationQualite=".unNombreSinon0ouNA($_POST['evaluationQualite']).",
				EvaluationDelais=".unNombreSinon0ouNA($_POST['evaluationDelais']).",
				EvaluationCompetencePersonnel=".unNombreSinon0ouNA($_POST['evaluationCompetencePersonnel']).",
				EvaluationAutonomie=".unNombreSinon0ouNA($_POST['evaluationAutonomie']).",
				EvaluationAnticipation=".unNombreSinon0ouNA($_POST['evaluationAnticipation']).",
				EvaluationCommunication=".unNombreSinon0ouNA($_POST['evaluationCommunication'])."
				".$reqPJ."
				".$reqPJPRM."
				".$reqPJSatisfactionClient."
				WHERE Id_Prestation=".$_POST['prestation']." 
				AND Annee=".$_POST['annee']." 
				AND Mois=".$_POST['mois']." ";
			$result=mysqli_query($bdd,$req);
			$IdMoisPrestation=$_POST['Id_MoisPrestation'];
		}
		else{
			$req="INSERT INTO moris_moisprestation (Id_Createur,DateCreation,Id_Prestation,Annee,Mois,RefCDC,Sigle,IntituleCDC,Id_Contrat,Id_Programme,
				AcheteurClient,DonneurOrdre,Id_CoorEquipe,Id_RespProjet,Id_EntiteAchat,MailAcheteur,MailDO,
				InterneCurrent,SubContractorCurrent,M1,M2,M3,M4,M5,M6,BesoinEffectif,ChargeDesactive,ProductiviteDesactive,PasActivite,
				TempsAlloue,TempsPasse,TempsObjectif,CommentaireProductivite,
				ObjectifClientOTD,NbLivrableConformeOTD,NbLivrableToleranceOTD,NbRetourClientOTD,CauseOTD,ActionOTD,ObjectifToleranceOTD,
				ObjectifClientOQD,NbLivrableConformeOQD,NbLivrableToleranceOQD,NbRetourClientOQD,CauseOQD,ActionOQD,ObjectifToleranceOQD,
				ModeCalculOTD,ModeCalculOQD,
				TendanceManagement,EvenementManagement,PasAT,PasOTD,PasOQD,
				NbXTableauPolyvalence,NbLTableauPolyvalence,NbMonoCompetence,TauxQualif,CommentairePlanActionFormation,
				DerniereDatePRM,DerniereDateEvaluation,PeriodicitePRM,DateEnvoiDemandeSatisfaction,
				FormatAT,
				EvaluationQualite,EvaluationDelais,EvaluationCompetencePersonnel,EvaluationAutonomie,EvaluationAnticipation,EvaluationCommunication,
				PointFortSatisfaction,PointFaibleSatisfaction,CommentaireSatisfaction,
				PieceJointeSQCDPF,PieceJointeDernierePRM,PieceJointeSatisfactionPRM
				) 
				VALUES (".$_SESSION['Id_Personne'].",'".date('Y-m-d')."',".$_POST['prestation'].",".$_POST['annee'].",".$_POST['mois'].",'".addslashes($_POST['refCDC'])."','".addslashes($_POST['sigle'])."','".addslashes($_POST['intituleCDC'])."',".addslashes($_POST['contrat']).",".addslashes($_POST['programme']).",
					'".addslashes($_POST['acheteurClient'])."','".addslashes($_POST['donneurOrdre'])."',
					".$_POST['coorEquipe'].",".$_POST['respProjet'].",".$_POST['entiteAchat'].",'".addslashes($_POST['mailAcheteur'])."','".addslashes($_POST['mailDO'])."',
					".unNombreSinon0($_POST['interneCurrent']).",".unNombreSinon0($_POST['subContractorCurrent']).",".unNombreSinon0($_POST['M1']).",".unNombreSinon0($_POST['M2']).",
					".unNombreSinon0($_POST['M3']).",".unNombreSinon0($_POST['M4']).",".unNombreSinon0($_POST['M5']).",".unNombreSinon0($_POST['M6']).",'".addslashes($_POST['besoinEffectif'])."',".$chargeActive.",".$productiviteActive.",".$pasActivite.",
					".unNombreSinon0($_POST['tempsAlloue']).",".unNombreSinon0($_POST['tempsPasse']).",".unNombreSinon0($_POST['tempsObjectif']).",'".addslashes($_POST['commentaireProductivite'])."',
					".unNombreSinon0($_POST['objectifClientOTD']).",
					".unNombreSinon0($_POST['nbLivrableConformeOTD']).",".unNombreSinon0($_POST['nbLivrableToleranceOTD']).",".unNombreSinon0($_POST['nbRetourClientOTD']).",'".addslashes($_POST['causeOTD'])."','".addslashes($_POST['actionOTD'])."',".unNombreSinon0($_POST['objectifToleranceOTD']).",
					".unNombreSinon0($_POST['objectifClientOQD']).",".unNombreSinon0($_POST['nbLivrableConformeOQD']).",".unNombreSinon0($_POST['nbLivrableToleranceOQD']).",".unNombreSinon0($_POST['nbRetourClientOQD']).",
					'".addslashes($_POST['causeOQD'])."','".addslashes($_POST['actionOQD'])."',".unNombreSinon0($_POST['objectifToleranceOQD']).",'".addslashes($_POST['modeCalculOTD'])."','".addslashes($_POST['modeCalculOQD'])."',
					'".$tendance."','".addslashes($_POST['evenementManagement'])."','".$PasAT."','".$PasOTD."','".$PasOQD."',
					".unNombreSinon0($_POST['nbXTableauPolyvalence']).",".unNombreSinon0($_POST['nbLTableauPolyvalence']).",".unNombreSinon0($_POST['nbMonoCompetence']).",".unNombreSinon0($_POST['tauxQualif2']).",'".addslashes($_POST['commentairePlanActionFormation'])."',
					'".TrsfDate_($_POST['derniereDatePRM'])."','".TrsfDate_($_POST['derniereDateEvaluation'])."',
					'".$_POST['periodicitePRM']."','".TrsfDate_($_POST['dateEnvoiDemandeSatisfaction'])."',".$_POST['formatSatisfaction'].",".unNombreSinon0ouNA($_POST['evaluationQualite']).",".unNombreSinon0ouNA($_POST['evaluationDelais']).",".unNombreSinon0ouNA($_POST['evaluationCompetencePersonnel']).",
					".unNombreSinon0ouNA($_POST['evaluationAutonomie']).",".unNombreSinon0ouNA($_POST['evaluationAnticipation']).",".unNombreSinon0ouNA($_POST['evaluationCommunication']).",'".addslashes($_POST['pointFort'])."','".addslashes($_POST['pointFaible'])."','".addslashes($_POST['CommentaireSatisfaction'])."',
					'".$fichierSQCDPF."','".$fichierPRM."','".$fichierSatisfactionClient."'
					)
			";

			$result=mysqli_query($bdd,$req);
			$IdMoisPrestation=mysqli_insert_id($bdd);
		}
		
		//Ajout ou Mise à jour des AT 
		for($i=1;$i<=4;$i++){
			if($_POST['Id_AT'.$i]==0){
				//Ajout
				if($_POST['dateAT'.$i]<>"" && $_POST['personne'.$i]<>"0"){
					$avecArret=0;
					$accidentTrajet=0;
					if(isset($_POST['avecArret'.$i])){$avecArret=1;}
					if(isset($_POST['accidentTrajet'.$i])){$accidentTrajet=1;}
					
					$req="INSERT INTO moris_moisprestation_securite (Id_MoisPrestation,DateCreation,Id_Createur,DateAT,Id_Personne,AvecArret,AccidentTrajet,Description)
						VALUES (".$IdMoisPrestation.",'".date('Y-m-d')."',".$_SESSION['Id_Personne'].",'".TrsfDate_($_POST['dateAT'.$i])."',".$_POST['personne'.$i].",".$avecArret.",
						".$accidentTrajet.",'".addslashes($_POST['description'.$i])."') ";
					$result=mysqli_query($bdd,$req);
				}
			}
			else{
				//Update
				if($_POST['dateAT'.$i]<>"" && $_POST['personne'.$i]<>"0"){
					$avecArret=0;
					$accidentTrajet=0;
					if(isset($_POST['avecArret'.$i])){$avecArret=1;}
					if(isset($_POST['accidentTrajet'.$i])){$accidentTrajet=1;}
					
					$req="UPDATE moris_moisprestation_securite 
						SET DateAT='".TrsfDate_($_POST['dateAT'.$i])."',
						Id_Personne=".$_POST['personne'.$i].",
						AvecArret=".$avecArret.",
						AccidentTrajet=".$accidentTrajet.",
						Description='".addslashes($_POST['description'.$i])."'
						WHERE Id=".$_POST['Id_AT'.$i];
					$result=mysqli_query($bdd,$req);
				}
				else{
					//Suppr 
					$req="UPDATE moris_moisprestation_securite 
						SET Suppr=1,
						Id_Suppr=".$_SESSION['Id_Personne'].",
						DateSuppr='".date('Y-m-d')."'
						WHERE Id=".$_POST['Id_AT'.$i];
					$result=mysqli_query($bdd,$req);
				}
			}
		}
		
		//Suppression des OTD et OQD
		$req="DELETE FROM moris_moisprestation_otdoqd WHERE Id_MoisPrestation=".$IdMoisPrestation;
		$result=mysqli_query($bdd,$req);
		//Ajout des OTD 
		for($i=0;$i<100;$i++){
			$PasOTD=0;
			if(isset($_POST['PasOTD'.$i])){$PasOTD=1;}
			if($_POST['LibelleOTD'.$i]<>""){
				$req="INSERT INTO moris_moisprestation_otdoqd (Id_MoisPrestation,Libelle,bOQD,ObjectifClient,NbLivrableConforme,NbLivrableTolerance,NbRetourClient,ObjectifTolerance,PasLivrable)
					VALUES (".$IdMoisPrestation.",'".addslashes($_POST['LibelleOTD'.$i])."',0,".unNombreSinon0($_POST['objectifClientOTD'.$i]).",".unNombreSinon0($_POST['nbLivrableConformeOTD'.$i])."
					,".unNombreSinon0($_POST['nbLivrableToleranceOTD'.$i]).",".unNombreSinon0($_POST['nbRetourClientOTD'.$i]).",".unNombreSinon0($_POST['objectifToleranceOTD'.$i]).",".$PasOTD.") ";
				$result=mysqli_query($bdd,$req);
			}
		}
		//Ajout des OQD 
		for($i=0;$i<100;$i++){
			$PasOQD=0;
			if(isset($_POST['PasOQD'.$i])){$PasOQD=1;}
			if($_POST['LibelleOQD'.$i]<>""){
				$req="INSERT INTO moris_moisprestation_otdoqd (Id_MoisPrestation,Libelle,bOQD,ObjectifClient,NbLivrableConforme,NbLivrableTolerance,NbRetourClient,ObjectifTolerance,PasLivrable)
					VALUES (".$IdMoisPrestation.",'".addslashes($_POST['LibelleOQD'.$i])."',1,".unNombreSinon0($_POST['objectifClientOQD'.$i]).",".unNombreSinon0($_POST['nbLivrableConformeOQD'.$i])."
					,".unNombreSinon0($_POST['nbLivrableToleranceOQD'.$i]).",".unNombreSinon0($_POST['nbRetourClientOQD'.$i]).",".unNombreSinon0($_POST['objectifToleranceOQD'.$i]).",".$PasOQD.") ";
				$result=mysqli_query($bdd,$req);
			}
		}
		
		//Avant suppression récupération des familles
		$req="SELECT Id 
			FROM moris_famille 
			WHERE Suppr=0 
			OR Id IN (SELECT Id_Famille FROM moris_moisprestation_famille WHERE Id_MoisPrestation=".$IdMoisPrestation." )";
		$resultFamille=mysqli_query($bdd,$req);
		
		//Suppression des familles
		$req="DELETE FROM moris_moisprestation_famille WHERE Id_MoisPrestation=".$IdMoisPrestation;
		$result=mysqli_query($bdd,$req);
		
		//-------Ajout des familles---------//
		$tabColonnes = array("M", "M1", "M2", "M3", "M4", "M5", "M6");
		//Ajout des indéfinis si il y a des données Interne/Externe
		$sommeInterne=0;
		$sommeExterne=0;
		foreach($tabColonnes as $col){
			$sommeInterne+=unNombreSinon0($_POST['interneIndefini'.$col])+unNombreSinon0($_POST['interneIndefiniCapa'.$col]);
			$sommeExterne+=unNombreSinon0($_POST['externeIndefini'.$col])+unNombreSinon0($_POST['externeIndefiniCapa'.$col]);
		}
		if($sommeInterne>0){
			$req="INSERT INTO moris_moisprestation_famille (Id_MoisPrestation,Id_Famille,Externe,M,M1,M2,M3,M4,M5,M6,CapaM,CapaM1,CapaM2,CapaM3,CapaM4,CapaM5,CapaM6) 
				VALUES (".$IdMoisPrestation.",0,0,".unNombreSinon0($_POST['interneIndefiniM']).",".unNombreSinon0($_POST['interneIndefiniM1'])."
				,".unNombreSinon0($_POST['interneIndefiniM2']).",".unNombreSinon0($_POST['interneIndefiniM3']).",".unNombreSinon0($_POST['interneIndefiniM4'])."
				,".unNombreSinon0($_POST['interneIndefiniM5']).",".unNombreSinon0($_POST['interneIndefiniM6'])."
				,".unNombreSinon0($_POST['interneIndefiniCapaM']).",".unNombreSinon0($_POST['interneIndefiniCapaM1'])."
				,".unNombreSinon0($_POST['interneIndefiniCapaM2']).",".unNombreSinon0($_POST['interneIndefiniCapaM3'])."
				,".unNombreSinon0($_POST['interneIndefiniCapaM4']).",".unNombreSinon0($_POST['interneIndefiniCapaM5']).",".unNombreSinon0($_POST['interneIndefiniCapaM6']).") ";
			$result=mysqli_query($bdd,$req);
		}
		if($sommeExterne>0){
			$req="INSERT INTO moris_moisprestation_famille (Id_MoisPrestation,Id_Famille,Externe,M,M1,M2,M3,M4,M5,M6,CapaM,CapaM1,CapaM2,CapaM3,CapaM4,CapaM5,CapaM6) 
				VALUES (".$IdMoisPrestation.",0,1,".unNombreSinon0($_POST['externeIndefiniM']).",".unNombreSinon0($_POST['externeIndefiniM1'])."
				,".unNombreSinon0($_POST['externeIndefiniM2']).",".unNombreSinon0($_POST['externeIndefiniM3']).",".unNombreSinon0($_POST['externeIndefiniM4'])."
				,".unNombreSinon0($_POST['externeIndefiniM5']).",".unNombreSinon0($_POST['externeIndefiniM6'])."
				,".unNombreSinon0($_POST['externeIndefiniCapaM'])."
				,".unNombreSinon0($_POST['externeIndefiniCapaM1']).",".unNombreSinon0($_POST['externeIndefiniCapaM2'])."
				,".unNombreSinon0($_POST['externeIndefiniCapaM3']).",".unNombreSinon0($_POST['externeIndefiniCapaM4'])."
				,".unNombreSinon0($_POST['externeIndefiniCapaM5']).",".unNombreSinon0($_POST['externeIndefiniCapaM6']).") ";
			$result=mysqli_query($bdd,$req);
		}
		//Ajout de chaques familles si il y a des données Interne/Externe
		$nbFamille=mysqli_num_rows($resultFamille);
		if($nbFamille>0){
			while($rowFamille=mysqli_fetch_array($resultFamille)){
				$sommeInterne=0;
				$sommeExterne=0;
				foreach($tabColonnes as $col){
					$sommeInterne+=unNombreSinon0($_POST['interne'.$rowFamille['Id'].$col])+unNombreSinon0($_POST['interne'.$rowFamille['Id']."Capa".$col]);
					$sommeExterne+=unNombreSinon0($_POST['externe'.$rowFamille['Id'].$col])+unNombreSinon0($_POST['externe'.$rowFamille['Id']."Capa".$col]);
				}
				if($sommeInterne>0){
					$req="INSERT INTO moris_moisprestation_famille (Id_MoisPrestation,Id_Famille,Externe,M,M1,M2,M3,M4,M5,M6,CapaM,CapaM1,CapaM2,CapaM3,CapaM4,CapaM5,CapaM6) 
						VALUES (".$IdMoisPrestation.",".$rowFamille['Id'].",0,".unNombreSinon0($_POST['interne'.$rowFamille['Id'].'M']).",".unNombreSinon0($_POST['interne'.$rowFamille['Id'].'M1'])."
						,".unNombreSinon0($_POST['interne'.$rowFamille['Id'].'M2']).",".unNombreSinon0($_POST['interne'.$rowFamille['Id'].'M3']).",".unNombreSinon0($_POST['interne'.$rowFamille['Id'].'M4'])."
						,".unNombreSinon0($_POST['interne'.$rowFamille['Id'].'M5']).",".unNombreSinon0($_POST['interne'.$rowFamille['Id'].'M6'])."
						,".unNombreSinon0($_POST['interne'.$rowFamille['Id'].'CapaM']).",".unNombreSinon0($_POST['interne'.$rowFamille['Id'].'CapaM1'])."
						,".unNombreSinon0($_POST['interne'.$rowFamille['Id'].'CapaM2']).",".unNombreSinon0($_POST['interne'.$rowFamille['Id'].'CapaM3'])."
						,".unNombreSinon0($_POST['interne'.$rowFamille['Id'].'CapaM4'])."
						,".unNombreSinon0($_POST['interne'.$rowFamille['Id'].'CapaM5']).",".unNombreSinon0($_POST['interne'.$rowFamille['Id'].'CapaM6']).") ";
					$result=mysqli_query($bdd,$req);
				}
				if($sommeExterne>0){
					$req="INSERT INTO moris_moisprestation_famille (Id_MoisPrestation,Id_Famille,Externe,M,M1,M2,M3,M4,M5,M6,CapaM,CapaM1,CapaM2,CapaM3,CapaM4,CapaM5,CapaM6) 
				VALUES (".$IdMoisPrestation.",".$rowFamille['Id'].",1,".unNombreSinon0($_POST['externe'.$rowFamille['Id'].'M']).",".unNombreSinon0($_POST['externe'.$rowFamille['Id'].'M1'])."
						,".unNombreSinon0($_POST['externe'.$rowFamille['Id'].'M2']).",".unNombreSinon0($_POST['externe'.$rowFamille['Id'].'M3']).",".unNombreSinon0($_POST['externe'.$rowFamille['Id'].'M4'])."
						,".unNombreSinon0($_POST['externe'.$rowFamille['Id'].'M5']).",".unNombreSinon0($_POST['externe'.$rowFamille['Id'].'M6'])."
						,".unNombreSinon0($_POST['externe'.$rowFamille['Id'].'CapaM']).",".unNombreSinon0($_POST['externe'.$rowFamille['Id'].'CapaM1'])."
						,".unNombreSinon0($_POST['externe'.$rowFamille['Id'].'CapaM2']).",".unNombreSinon0($_POST['externe'.$rowFamille['Id'].'CapaM3'])."
						,".unNombreSinon0($_POST['externe'.$rowFamille['Id'].'CapaM4'])."
						,".unNombreSinon0($_POST['externe'.$rowFamille['Id'].'CapaM5']).",".unNombreSinon0($_POST['externe'.$rowFamille['Id'].'CapaM6']).") ";
					$result=mysqli_query($bdd,$req);
				}
			}
		}
		
		
		//Plan de prévention 
		$req="SELECT Id, RefPdp,DateValidite
			FROM moris_pdp 
			WHERE Id_Prestation=".$_POST['prestation']." 
			AND Annee=".$_POST['annee']." 
			AND Mois=".$_POST['mois']." ";
		$resultPdp=mysqli_query($bdd,$req);
		$nbResultaPdp=mysqli_num_rows($resultPdp);
		if($nbResultaPdp>0){
			$req="UPDATE moris_pdp SET RefPdp='".$_POST['planPrevention']."',DateValidite='".TrsfDate_($_POST['dateValidite'])."' WHERE Id_Prestation=".$_POST['prestation']." AND Annee=".$_POST['annee']." AND Mois=".$_POST['mois']." ";
			$result=mysqli_query($bdd,$req);
		}
		else{
			$req="INSERT INTO moris_pdp (Annee,Mois,Id_Prestation,RefPdp,DateValidite) VALUES (".$_POST['annee'].",".$_POST['mois'].",".$_POST['prestation'].",'".$_POST['planPrevention']."','".TrsfDate_($_POST['dateValidite'])."')";
			$result=mysqli_query($bdd,$req);
		}
		
		if(isset($_POST['Btn_Verrouiller'])){
			$req="UPDATE moris_moisprestation 
				SET Verouillage=1
				WHERE Id=".$IdMoisPrestation;
			$result=mysqli_query($bdd,$req);
			
		}
		if(isset($_POST['Btn_Deverrouiller'])){
			$req="UPDATE moris_moisprestation 
				SET Verouillage=0
				WHERE Id=".$IdMoisPrestation;
			$result=mysqli_query($bdd,$req);
			
		}
	}
}

?>
<table align="center" width="100%" cellpadding="0" cellspacing="0">
<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
<input type="hidden" name="MAX_FILE_SIZE" value="100000000">
	<tr><td height="10"></td></tr>
	<tr>
		<td align="center">
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<?php 
						$req="SELECT FR,EN
						FROM moris_aideparagraphe
						WHERE moris_aideparagraphe.NomParagraphe='INFORMATIONS GENERALE' ";
						$resultAide=mysqli_query($bdd,$req);
						$LigneAide=mysqli_fetch_array($resultAide);
					?>
					<td width="25%" valign="top" height="550px;" rowspan="2">
						<table class="TableCompetences" width="99%" height="550px;" cellpadding="0" cellspacing="0">
							<tr>
								<td class="Libelle" height="5%" style="font-size:15px;"><?php if($_SESSION['Langue']=="EN"){echo "GENERAL INFORMATION";}else{echo "INFORMATIONS GENERALES";} ?></td>
								<td style="cursor:pointer;" align="right"><div onclick="AfficheAide('Generale')"><img id="Generale" width="30px" src='../../Images/Aide2.png' border='0' /></div></td>
							</tr>
							<tr class="Generale" style="display:none;"><td height="4"></td></tr>
							<tr class="Generale" style="display:none;"><td bgcolor="#d5f2ff" style="border-top:2px dotted #0b6acb;" colspan="2" height="4"></td></tr>
							<tr class="Generale" style="display:none;">
								<td colspan="2" bgcolor="#d5f2ff" style="color:#000000;">
								<?php if($_SESSION['Langue']=="EN"){echo nl2br(str_replace("\\","",stripslashes($LigneAide['EN'])));}else{echo nl2br(str_replace("\\","",stripslashes($LigneAide['FR'])));} ?>
								</td>
							</tr>
							<tr><td style="border-bottom:2px solid #0b6acb;" colspan="2" height="4"></td></tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td  height="95%" colspan="2">
									<table width="95%" cellpadding="0" cellspacing="0" align="center" style="border:1px solid black;">
										<tr>
											<td class="Libelle" style="border:1px solid black;" align="center" width="30%"><?php if($_SESSION['Langue']=="EN"){echo "Year";}else{echo "Année";} ?></td>
											<td style="border:1px solid black;" width="70%">
												<select id="annee" name="annee" onchange="submit();">
													<?php
														$annee=$_SESSION['MORIS_Annee2'];
														if($_POST){$annee=$_POST['annee'];}
														$_SESSION['MORIS_Annee2']=$annee;
													?>
													<option value="<?php echo date('Y')-1; ?>" <?php if($annee==date('Y')-1){echo "selected";} ?>><?php echo date('Y')-1; ?></option>
													<option value="<?php echo date('Y'); ?>" <?php if($annee==date('Y')){echo "selected";} ?>><?php echo date('Y'); ?></option>
													<option value="<?php echo date('Y')+1; ?>" <?php if($annee==date('Y')+1){echo "selected";} ?>><?php echo date('Y')+1; ?></option>
												</select>
											</td>
										</tr>
										<tr>
											<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Month";}else{echo "Mois";} ?></td>
											<td style="border:1px solid black;">
												<select id="mois" name="mois" onchange="submit();">
													<?php
														if($_SESSION["Langue"]=="EN"){
															$arrayMois=array("January","February","March","April","May","June","July","August","September","October","November","December");
															
														}
														else{
															$arrayMois=array("Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
														}
														$mois=$_SESSION['MORIS_Mois2'];
														if($_POST){$mois=$_POST['mois'];}
														$_SESSION['MORIS_Mois2']=$mois;
														
														for($i=0;$i<=11;$i++){
															$numMois=$i+1;
															if($numMois<10){$numMois="0".$numMois;}
															echo "<option value='".$numMois."'";
															if($mois== ($i+1)){echo " selected ";}
															echo ">".$arrayMois[$i]."</option>\n";
														}
													?>
												</select>
											</td>
										</tr>
										<tr>
											<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Site";}else{echo "Prestation";} ?></td>
											<td style="border:1px solid black;">
											<select class="prestation" style="width:130px;" name="prestation" onchange="submit();">
											<?php 
												$req="SELECT Id
														FROM new_competences_personne_poste_plateforme
														WHERE Id_Personne=".$_SESSION['Id_Personne']."
														AND Id_Plateforme=17
														AND Id_Poste IN (9,15,27,41,44)";
												$resultRespSG=mysqli_query($bdd,$req);
												$nbRespSG=mysqli_num_rows($resultRespSG);
												
												if(mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Id FROM moris_administrateur WHERE Id_Personne=".$IdPersonneConnectee))>0 || $nbRespSG>0){
													$req="SELECT Id,Libelle
													FROM new_competences_prestation
													WHERE new_competences_prestation.UtiliseMORIS=1
													AND (
														SELECT COUNT(DateDebut) 
														FROM moris_datesuivi 
														WHERE Id_Prestation=new_competences_prestation.Id
														AND Suppr=0 
														AND CONCAT(YEAR(DateDebut),'_',IF(MONTH(DateDebut)<10,CONCAT('0',MONTH(DateDebut)),MONTH(DateDebut)))<='".date($_SESSION['MORIS_Annee2'].'_'.$_SESSION['MORIS_Mois2'])."'
														AND (CONCAT(YEAR(DateFin),'_',IF(MONTH(DateFin)<10,CONCAT('0',MONTH(DateFin)),MONTH(DateFin)))>='".date($_SESSION['MORIS_Annee2'].'_'.$_SESSION['MORIS_Mois2'])."' OR DateFin<='0001-01-01')
													)>0
													ORDER BY Libelle;";
												}
												else{
													$req="SELECT Id,Libelle
													FROM new_competences_prestation
													WHERE new_competences_prestation.UtiliseMORIS=1
													AND (
														SELECT COUNT(DateDebut) 
														FROM moris_datesuivi 
														WHERE Id_Prestation=new_competences_prestation.Id
														AND Suppr=0 
														AND CONCAT(YEAR(DateDebut),'_',IF(MONTH(DateDebut)<10,CONCAT('0',MONTH(DateDebut)),MONTH(DateDebut)))<='".date($_SESSION['MORIS_Annee2'].'_'.$_SESSION['MORIS_Mois2'])."'
														AND (CONCAT(YEAR(DateFin),'_',IF(MONTH(DateFin)<10,CONCAT('0',MONTH(DateFin)),MONTH(DateFin)))>='".date($_SESSION['MORIS_Annee2'].'_'.$_SESSION['MORIS_Mois2'])."' OR DateFin<='0001-01-01')
													)>0
													AND ((SELECT COUNT(Id) 
														FROM new_competences_personne_poste_prestation 
														WHERE Id_Personne=".$_SESSION['Id_Personne']."
														AND Id_Prestation=new_competences_prestation.Id 
														AND Id_Poste IN (2,3,4,46)
														)>0
													OR 	
														(SELECT COUNT(Id) 
														FROM new_competences_personne_poste_plateforme
														WHERE Id_Personne=".$_SESSION['Id_Personne']."
														AND Id_Plateforme=new_competences_prestation.Id_Plateforme 
														AND Id_Poste IN (6,27)
														)>0
													OR 
														(SELECT COUNT(Id) 
														FROM new_competences_personne_poste_prestation 
														WHERE Id_Personne=".$_SESSION['Id_Personne']."
														AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation)=new_competences_prestation.Id_Plateforme 
														AND Id_Poste IN (5)
														)>0
													)
													ORDER BY Libelle;";
												}
												$resultPrestation=mysqli_query($bdd,$req);
												$nbPrestation=mysqli_num_rows($resultPrestation);
												
												$PrestationSelect = 0;
												$Selected = "";
												
												$PrestationSelect=$_SESSION['MORIS_Prestation'];
												if($_POST){$PrestationSelect=$_POST['prestation'];}
												$_SESSION['MORIS_Prestation']=$PrestationSelect;	
												
												if ($nbPrestation > 0)
												{
													while($row=mysqli_fetch_array($resultPrestation))
													{
														$selected="";
														if($PrestationSelect=="" || $PrestationSelect=="0"){$PrestationSelect=$row['Id'];}
														if($PrestationSelect<>""){if($PrestationSelect==$row['Id']){$selected="selected";}}
														$presta=substr($row['Libelle'],0,strpos($row['Libelle']," "));
														echo "<option value='".$row['Id']."' ".$selected.">".$presta."</option>\n";
													}
												 }
												 
												 $readonly="readonly='readonly'";
												 $visible="style='display:none'";
												if((mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Id FROM moris_administrateur WHERE Id_Personne=".$IdPersonneConnectee))>0) ||
												(mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Id FROM new_competences_personne_poste_prestation WHERE Id_Prestation=".$PrestationSelect." AND Id_Personne=".$IdPersonneConnectee."  AND (Id_Poste=4) "))>=0)){$readonly="";$visible="";}
											?>
											</select>
											</td>
										</tr>
									</table>
									<br>
									<table width="95%" cellpadding="0" cellspacing="0" align="center" style="border:1px solid black;">
										<?php 
											$req="SELECT Id,Libelle, RefCDC,IntituleCDC,AcheteurClient,DonneurOrdre,Sigle,Id_Contrat,
												(SELECT CONCAT(Num,' - ',Libelle) FROM moris_famille_r03 WHERE Id=Id_FamilleR03) AS FamilleR03,
												(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme,
												(SELECT Libelle FROM moris_divisionclient WHERE Id=Id_DivisionClient) AS DivisionClient,
												(SELECT Libelle FROM moris_contrat WHERE Id=Id_Contrat) AS Contrat,
												(SELECT Libelle FROM moris_entiteachat WHERE Id=Id_EntiteAchat) AS EntiteAchat,
												Code_Analytique,Id_Programme,ChargeADesactive,ProductiviteADesactive,
												(SELECT Id_Personne
												FROM new_competences_personne_poste_prestation 
												WHERE new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id  
												AND Id_Poste=2
												AND Backup=0 LIMIT 1) AS CoorEquipe,
												(SELECT Id_Personne 
												FROM new_competences_personne_poste_prestation 
												WHERE new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id  
												AND Id_Poste=4
												AND Backup=0 LIMIT 1) AS RespProjet,
												(SELECT Libelle FROM moris_client WHERE Id=Id_Client) AS Client,
												Id_EntiteAchat,MailAcheteur,MailDO,ToleranceOTDOQD,
												OTDOQDADesactive,ManagementADesactive,CompetenceADesactive,SecuriteADesactive,PRMADesactive,NCADesactive
											FROM new_competences_prestation
											WHERE new_competences_prestation.Id=".$PrestationSelect." ";
											$result=mysqli_query($bdd,$req);
											$nbResulta=mysqli_num_rows($result);
											$Ligne=mysqli_fetch_array($result);
											
											$req="SELECT Id,RefCDC,IntituleCDC,AcheteurClient,DonneurOrdre,Sigle,Id_Contrat,
												Id_CoorEquipe,Id_RespProjet,Id_EntiteAchat,MailAcheteur,MailDO,
												PermanentCurrent+TemporyCurrent+InterneCurrent AS InterneCurrent,
												SubContractorCurrent,M1,M2,M3,M4,M5,M6,BesoinEffectif,Id_Programme,
												TempsAlloue,TempsPasse,TempsObjectif,CommentaireProductivite,
												ObjectifClientOTD,NbLivrableOTD,NbRetourClientOTD,CauseOTD,ActionOTD,OTD,PasOTD,ObjectifToleranceOTD,
												ObjectifClientOQD,NbLivrableOQD,NbRetourClientOQD,CauseOQD,ActionOQD,OQD,PasOQD,ObjectifToleranceOQD,
												ModeCalculOTD,ModeCalculOQD,NbLivrableToleranceOTD,NbLivrableToleranceOQD,
												TendanceManagement,EvenementManagement,TauxQualif,
												IF(Annee<2022,NbLivrableOTD-NbRetourClientOTD,NbLivrableConformeOTD) AS NbLivrableConformeOTD,
												IF(Annee<2022,NbLivrableOQD-NbRetourClientOQD,NbLivrableConformeOQD) AS NbLivrableConformeOQD,
												PieceJointeSQCDPF,FormatAT,
												NbXTableauPolyvalence,NbLTableauPolyvalence,NbMonoCompetence,CommentairePlanActionFormation,
												DerniereDatePRM,DerniereDateEvaluation,ProchaineDatePRM,PeriodicitePRM,PieceJointeSatisfactionPRM,PieceJointeDernierePRM,DateEnvoiDemandeSatisfaction,
												IF(EvaluationQualite=-1,'NA',EvaluationQualite) AS EvaluationQualite,
												IF(EvaluationDelais=-1,'NA',EvaluationDelais) AS EvaluationDelais,
												IF(EvaluationCompetencePersonnel=-1,'NA',EvaluationCompetencePersonnel) AS EvaluationCompetencePersonnel,
												IF(EvaluationAutonomie=-1,'NA',EvaluationAutonomie) AS EvaluationAutonomie,
												IF(EvaluationAnticipation=-1,'NA',EvaluationAnticipation) AS EvaluationAnticipation,
												IF(EvaluationCommunication=-1,'NA',EvaluationCommunication) AS EvaluationCommunication,
												Verouillage,PasAT,PasNC,PointFortSatisfaction,PointFaibleSatisfaction,CommentaireSatisfaction,PasActivite
											FROM moris_moisprestation
											WHERE moris_moisprestation.Id_Prestation=".$PrestationSelect." 
											AND Annee=".$annee." 
											AND Mois=".$mois."
											AND Suppr=0 											
											";
											$result=mysqli_query($bdd,$req);
											$nbResultaMoisPresta=mysqli_num_rows($result);
											if($nbResultaMoisPresta>0){$LigneMoisPrestation=mysqli_fetch_array($result);}
											
											$annee_M_1=$annee;
											$mois_M_1=$mois-1;
											if($mois_M_1==0){
												$annee_M_1=$annee-1;
												$mois_M_1=12;
											}
											$req="SELECT Id,RefCDC,IntituleCDC,AcheteurClient,DonneurOrdre,Sigle,Id_Contrat,
												Id_CoorEquipe,Id_RespProjet,Id_EntiteAchat,MailAcheteur,MailDO,
												PermanentCurrent+TemporyCurrent+InterneCurrent AS InterneCurrent,
												SubContractorCurrent,BesoinEffectif,Id_Programme,
												M1,M2,M3,M4,M5,M6,
												TempsAlloue,TempsPasse,TempsObjectif,CommentaireProductivite,
												ObjectifClientOTD,NbLivrableOTD,NbRetourClientOTD,CauseOTD,ActionOTD,OTD,PasOTD,ObjectifToleranceOTD,
												ObjectifClientOQD,NbLivrableOQD,NbRetourClientOQD,CauseOQD,ActionOQD,OQD,PasOQD,ObjectifToleranceOQD,
												ModeCalculOTD,ModeCalculOQD,NbLivrableConformeOTD,NbLivrableConformeOQD,NbLivrableToleranceOTD,NbLivrableToleranceOQD,
												TendanceManagement,EvenementManagement,TauxQualif,
												PieceJointeSQCDPF,FormatAT,
												NbXTableauPolyvalence,NbLTableauPolyvalence,NbMonoCompetence,CommentairePlanActionFormation,
												DerniereDatePRM,DerniereDateEvaluation,ProchaineDatePRM,PeriodicitePRM,PieceJointeSatisfactionPRM,PieceJointeDernierePRM,DateEnvoiDemandeSatisfaction,
												IF(EvaluationQualite=-1,'NA',EvaluationQualite) AS EvaluationQualite,
												IF(EvaluationDelais=-1,'NA',EvaluationDelais) AS EvaluationDelais,
												IF(EvaluationCompetencePersonnel=-1,'NA',EvaluationCompetencePersonnel) AS EvaluationCompetencePersonnel,
												IF(EvaluationAutonomie=-1,'NA',EvaluationAutonomie) AS EvaluationAutonomie,
												IF(EvaluationAnticipation=-1,'NA',EvaluationAnticipation) AS EvaluationAnticipation,
												IF(EvaluationCommunication=-1,'NA',EvaluationCommunication) AS EvaluationCommunication,
												Verouillage,PasAT,PasNC,PointFortSatisfaction,PointFaibleSatisfaction,CommentaireSatisfaction,PasActivite
											FROM moris_moisprestation
											WHERE moris_moisprestation.Id_Prestation=".$PrestationSelect." 
											AND Annee=".$annee_M_1." 
											AND Mois=".$mois_M_1."
											AND Suppr=0 											
											";
											$resultM1=mysqli_query($bdd,$req);
											$nbResultaMoisPrestaM1=mysqli_num_rows($resultM1);
											if($nbResultaMoisPrestaM1>0){$LigneMoisPrestationM1=mysqli_fetch_array($resultM1);}
											
											
											$anneeM_1=$annee;
											$moisM_1=$mois-1;
											if($moisM_1==0){
												$moisM_1=12;
												$anneeM_1=$anneeM_1-1;
											}
											$req="SELECT Id,RefCDC,IntituleCDC,AcheteurClient,DonneurOrdre,Sigle,Id_Contrat,
												Id_CoorEquipe,Id_RespProjet,Id_EntiteAchat,MailAcheteur,MailDO,
												PermanentCurrent+TemporyCurrent+InterneCurrent AS InterneCurrent,
												SubContractorCurrent,BesoinEffectif,
												M1,M2,M3,M4,M5,M6,BesoinEffectif,Id_Programme,
												TempsAlloue,TempsPasse,TempsObjectif,CommentaireProductivite,
												ObjectifClientOTD,NbLivrableOTD,NbRetourClientOTD,CauseOTD,ActionOTD,OTD,PasOTD,ObjectifToleranceOTD,
												ObjectifClientOQD,NbLivrableOQD,NbRetourClientOQD,CauseOQD,ActionOQD,OQD,PasOQD,ObjectifToleranceOQD,
												ModeCalculOTD,ModeCalculOQD,FormatAT,NbLivrableConformeOTD,NbLivrableConformeOQD,NbLivrableToleranceOTD,NbLivrableToleranceOQD,
												TendanceManagement,EvenementManagement,TauxQualif,
												PieceJointeSQCDPF,
												NbXTableauPolyvalence,NbLTableauPolyvalence,NbMonoCompetence,CommentairePlanActionFormation,
												DerniereDatePRM,DerniereDateEvaluation,ProchaineDatePRM,PeriodicitePRM,PieceJointeSatisfactionPRM,PieceJointeDernierePRM,DateEnvoiDemandeSatisfaction,
												IF(EvaluationQualite=-1,'NA',EvaluationQualite) AS EvaluationQualite,
												IF(EvaluationDelais=-1,'NA',EvaluationDelais) AS EvaluationDelais,
												IF(EvaluationCompetencePersonnel=-1,'NA',EvaluationCompetencePersonnel) AS EvaluationCompetencePersonnel,
												IF(EvaluationAutonomie=-1,'NA',EvaluationAutonomie) AS EvaluationAutonomie,
												IF(EvaluationAnticipation=-1,'NA',EvaluationAnticipation) AS EvaluationAnticipation,
												IF(EvaluationCommunication=-1,'NA',EvaluationCommunication) AS EvaluationCommunication,Verouillage
											FROM moris_moisprestation
											WHERE moris_moisprestation.Id_Prestation=".$PrestationSelect." 
											AND Annee=".$anneeM_1." 
											AND Mois=".$moisM_1."
											AND Suppr=0 											
											";

											$resultMois_1=mysqli_query($bdd,$req);
											$nbResultaMois_1Presta=mysqli_num_rows($resultMois_1);
											if($nbResultaMois_1Presta>0){$LigneMois_1Prestation=mysqli_fetch_array($resultMois_1);}
										?>
										<input type="hidden" name="Id_MoisPrestation" value="<?php if($nbResultaMoisPresta>0){echo $LigneMoisPrestation['Id'];}?>" />
										<input type="hidden" id="ChargeADesactive" name="ChargeADesactive" value="<?php if($nbResulta>0){echo $Ligne['ChargeADesactive'];}?>" />
										<input type="hidden" id="SecuriteADesactive" name="SecuriteADesactive" value="<?php if($nbResulta>0){echo $Ligne['SecuriteADesactive'];}?>" />
										<input type="hidden" id="AnneeMois" name="AnneeMois" value="<?php echo $annee."_".$mois; ?>" />
										<input type="hidden" name="ProductiviteADesactive" value="<?php if($nbResulta>0){echo $Ligne['ProductiviteADesactive'];}?>" />
										<input type="hidden" id="ManagementADesactive" name="ManagementADesactive" value="<?php if($nbResulta>0){echo $Ligne['ManagementADesactive'];}?>" />
										<input type="hidden" id="ToleranceOTDOQD" name="ToleranceOTDOQD" value="<?php if($nbResulta>0){echo $Ligne['ToleranceOTDOQD'];}?>" />
										<input type="hidden" id="PRMADesactive" name="PRMADesactive" value="<?php if($nbResulta>0){echo $Ligne['PRMADesactive'];}?>" />
										<input type="hidden" id="OTDOQDADesactive" name="OTDOQDADesactive" value="<?php if($nbResulta>0){echo $Ligne['OTDOQDADesactive'];}?>" />
										<tr>
											<td class="Libelle" style="border:1px solid black;" align="center" width="30%"><?php if($_SESSION['Langue']=="EN"){echo "UER/Department/Subsidiary";}else{echo "UER/Dept/Filiale";} ?></td>
											<td style="border:1px solid black;" width="70%">&nbsp;<?php echo $Ligne['Plateforme']; ?></td>
										</tr>
										<tr>
											<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Client";}else{echo "Client";} ?></td>
											<td style="border:1px solid black;">&nbsp;<?php echo $Ligne['Client']; ?></td>
										</tr>
										<tr>
											<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Family R03";}else{echo "Famille R03";} ?></td>
											<td style="border:1px solid black;">&nbsp;<?php echo $Ligne['FamilleR03']; ?></td>
										</tr>
										<tr>
											<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Customer department";}else{echo "Division client";} ?></td>
											<td style="border:1px solid black;">&nbsp;<?php echo $Ligne['DivisionClient']; ?></td>
										</tr>
										<tr>
											<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Contract";}else{echo "Contrat";} ?></td>
											<td style="border:1px solid black;">&nbsp;<?php echo $Ligne['Contrat']; ?></td>
										</tr>
										<tr>
											<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Purchasing entity";}else{echo "Entité achat";} ?></td>
											<td style="border:1px solid black;">&nbsp;<?php echo $Ligne['EntiteAchat']; ?></td>
										</tr>
									</table>
									<br>
									<table width="95%" cellpadding="0" cellspacing="0" align="center" style="border:1px solid black;">
										<tr>
											<td class="Libelle" style="border:1px solid black;" align="center" width="30%"><?php if($_SESSION['Langue']=="EN"){echo "Project manager";}else{echo "Nom du Resp. Projet";} ?></td>
											<td style="border:1px solid black;" width="70%">
												<select id="respProjet" name="respProjet">
													<?php
														
														$req="SELECT DISTINCT new_rh_etatcivil.Id, Nom, Prenom 
														FROM new_rh_etatcivil 
														WHERE Id IN (
															SELECT Id_Personne
															FROM new_competences_personne_poste_prestation
															WHERE new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
															AND new_competences_personne_poste_prestation.Id_Poste=4
															AND Id_Prestation=".$PrestationSelect."
															AND Id_Personne<>0) ";
														if($nbResultaMoisPresta>0){
															if($LigneMoisPrestation['Id_CoorEquipe']>0){
																$req.="OR Id=".$LigneMoisPrestation['Id_CoorEquipe']." ";
															}
														}
														$req.="ORDER BY Nom, Prenom;";
														$result=mysqli_query($bdd,$req);
														$nbResulta=mysqli_num_rows($result);
														if ($nbResulta>0){
															while($row=mysqli_fetch_array($result)){
																$selected="";
																if($nbResultaMoisPresta>0){
																	if($LigneMoisPrestation['Id_RespProjet']==$row['Id']){$selected="selected";}
																}
																else{
																}
																echo "<option value='".$row['Id']."' ".$selected.">".$row['Nom']." ".$row['Prenom']."</option>";
															}
														}
														else{
															echo"<option name='0' value='0'></option>";
														}	
													?>
												</select>
											</td>
										</tr>
										<tr>
											<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Team coordinator";}else{echo "Nom du Coor. d'équipe";} ?></td>
											<td style="border:1px solid black;">
												<select id="coorEquipe" name="coorEquipe">
													<?php
														$req="SELECT DISTINCT new_rh_etatcivil.Id, Nom, Prenom 
														FROM new_rh_etatcivil 
														WHERE Id IN (
															SELECT Id_Personne
															FROM new_competences_personne_poste_prestation
															WHERE new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
															AND new_competences_personne_poste_prestation.Id_Poste=2
															AND Id_Prestation=".$PrestationSelect."
															AND Id_Personne<>0) ";
														if($nbResultaMoisPresta>0){
															if($LigneMoisPrestation['Id_CoorEquipe']>0){
																$req.="OR Id=".$LigneMoisPrestation['Id_CoorEquipe']." ";
															}
														}
														$req.="ORDER BY Nom, Prenom;";
														$result=mysqli_query($bdd,$req);
														$nbResulta=mysqli_num_rows($result);
														
														if ($nbResulta>0){
															while($row=mysqli_fetch_array($result)){
																$selected="";
																if($nbResultaMoisPresta>0){
																	if($LigneMoisPrestation['Id_CoorEquipe']==$row['Id']){$selected="selected";}
																}
																else{
																}
																echo "<option value='".$row['Id']."' ".$selected.">".$row['Nom']." ".$row['Prenom']."</option>";
															}
														}
														else{
															echo"<option name='0' value='0'></option>";
														}
													?>
												</select>
											</td>
										</tr>
									</table>
									<br>
									<table width="95%" cellpadding="0" cellspacing="0" align="center" style="border:1px solid black;">
										<tr>
											<td class="Libelle" style="border:1px solid black;" align="center" width="30%"><?php if($_SESSION['Langue']=="EN"){echo "Siglum";}else{echo "Sigle";} ?></td>
											<td style="border:1px solid black;">
												<input type="texte" name="sigle" width="70%" id="sigle" size="15" <?php echo $readonly; ?> value="<?php if($nbResultaMoisPresta>0){echo stripslashes($LigneMoisPrestation['Sigle']);}elseif($nbResultaMoisPrestaM1>0){echo stripslashes($LigneMoisPrestationM1['Sigle']);}?>">
											</td>
										</tr>
										<tr>
											<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Reference project";}else{echo "Ref CDC";} ?></td>
											<td style="border:1px solid black;">
												<input type="texte" name="refCDC" id="refCDC" size="40" <?php echo $readonly; ?> value="<?php if($nbResultaMoisPresta>0){echo $LigneMoisPrestation['RefCDC'];}elseif($nbResultaMoisPrestaM1>0){echo $LigneMoisPrestationM1['RefCDC'];}?>">
											</td>
										</tr>
										<tr style="display:none;">
											<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Contract name";}else{echo "Nom du contrat";} ?></td>
											<td style="border:1px solid black;">
												<select id="contrat" name="contrat">
													<?php
														echo"<option name='0' value='0'></option>";
														$req="SELECT Id, Libelle FROM moris_contrat WHERE Suppr=0 OR Id=".$Ligne['Id_Contrat']." ORDER BY Libelle ";
														$result=mysqli_query($bdd,$req);
														$nbResulta=mysqli_num_rows($result);
														if ($nbResulta>0){
															while($row=mysqli_fetch_array($result)){
																$selected="";
																if($nbResultaMoisPresta>0){
																	if($LigneMoisPrestation['Id_Contrat']==$row['Id']){$selected="selected";}
																}
																else{
																	if($Ligne['Id_Contrat']==$row['Id']){$selected="selected";}
																}
																echo "<option value='".$row['Id']."' ".$selected.">".$row['Libelle']."</option>";
															}
														}
													?>
												</select>
											</td>
										</tr>
										<tr style="display:none;">
											<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Purchasing entity";}else{echo "Entité achat";} ?></td>
											<td style="border:1px solid black;">
												<select id="entiteAchat" name="entiteAchat">
													<?php
														echo"<option name='0' value='0'></option>";
														$req="SELECT Id, Libelle FROM moris_entiteachat WHERE Suppr=0 OR Id=".$Ligne['Id_EntiteAchat']." ORDER BY Libelle ";
														$result=mysqli_query($bdd,$req);
														$nbResulta=mysqli_num_rows($result);
														if ($nbResulta>0){
															while($row=mysqli_fetch_array($result)){
																$selected="";
																if($nbResultaMoisPresta>0){
																	if($LigneMoisPrestation['Id_EntiteAchat']==$row['Id']){$selected="selected";}
																}
																else{
																	if($Ligne['Id_EntiteAchat']==$row['Id']){$selected="selected";}
																}
																echo "<option value='".$row['Id']."' ".$selected.">".$row['Libelle']."</option>";
															}
														}
													?>
												</select>
											</td>
										</tr>
										<tr>
											<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "EGP Buyer in charge of contract";}else{echo "Acheteur client";} ?></td>
											<td style="border:1px solid black;">
												<input type="texte" name="acheteurClient" id="acheteurClient" <?php echo $readonly; ?> size="25" value="<?php if($nbResultaMoisPresta>0){echo $LigneMoisPrestation['AcheteurClient'];}elseif($nbResultaMoisPrestaM1>0){echo $LigneMoisPrestationM1['AcheteurClient'];}?>">
											</td>
										</tr>
										<tr>
											<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Buyer mail";}else{echo "Mail acheteur";} ?></td>
											<td style="border:1px solid black;">
												<input type="texte" name="mailAcheteur" id="mailAcheteur" <?php echo $readonly; ?> size="40" value="<?php if($nbResultaMoisPresta>0){echo $LigneMoisPrestation['MailAcheteur'];}elseif($nbResultaMoisPrestaM1>0){echo $LigneMoisPrestationM1['MailAcheteur'];}?>">
											</td>
										</tr>
										<tr>
											<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Customer";}else{echo "Donneur d'ordre";} ?></td>
											<td style="border:1px solid black;">
												<input type="texte" name="donneurOrdre" id="donneurOrdre" <?php echo $readonly; ?> size="25" value="<?php if($nbResultaMoisPresta>0){echo $LigneMoisPrestation['DonneurOrdre'];}elseif($nbResultaMoisPrestaM1>0){echo $LigneMoisPrestationM1['DonneurOrdre'];}?>">
											</td>
										</tr>
										<tr>
											<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Customer mail";}else{echo "Mail donneur d'ordre";} ?></td>
											<td style="border:1px solid black;">
												<input type="texte" name="mailDO" id="mailDO" <?php echo $readonly; ?> size="40" value="<?php if($nbResultaMoisPresta>0){echo $LigneMoisPrestation['MailDO'];}elseif($nbResultaMoisPrestaM1>0){echo $LigneMoisPrestationM1['MailDO'];}?>">
											</td>
										</tr>
										<tr>
											<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Activity label";}else{echo "Libellé activité";} ?></td>
											<td style="border:1px solid black;">
												<input type="texte" name="intituleCDC" id="intituleCDC" <?php echo $readonly; ?> size="40" value="<?php if($nbResultaMoisPresta>0){echo stripslashes($LigneMoisPrestation['IntituleCDC']);}elseif($nbResultaMoisPrestaM1>0){echo $LigneMoisPrestationM1['IntituleCDC'];}?>">
											</td>
										</tr>
										<tr>
											<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Program / Product";}else{echo "Programme / Produit";} ?></td>
											<td style="border:1px solid black;">
												<select id="programme" name="programme">
													<?php
														echo"<option name='0' value='0'></option>";
														$req="SELECT Id, Libelle FROM moris_programme WHERE Suppr=0 OR Id=".$Ligne['Id_Programme']." ORDER BY Libelle ";
														$result=mysqli_query($bdd,$req);
														$nbResulta=mysqli_num_rows($result);
														if ($nbResulta>0){
															while($row=mysqli_fetch_array($result)){
																$selected="";
																if($nbResultaMoisPresta>0){
																	if($LigneMoisPrestation['Id_Programme']==$row['Id']){$selected="selected";}
																}
																elseif($nbResultaMoisPrestaM1>0){
																	if($LigneMoisPrestationM1['Id_Programme']==$row['Id']){$selected="selected";}
																}
																echo "<option value='".$row['Id']."' ".$selected.">".$row['Libelle']."</option>";
															}
														}
													?>
												</select>
											</td>
										</tr>
									</table>
									<?php 
										$req="SELECT PlanPreventionADesactivite FROM new_competences_prestation WHERE Id=".$PrestationSelect." ";
										$resultPresta=mysqli_query($bdd,$req);
										$nbResultaPresta=mysqli_num_rows($resultPresta);
										$Pdp=1;
										if($nbResultaPresta>0){
											$rowPresta=mysqli_fetch_array($resultPresta);
											if($rowPresta['PlanPreventionADesactivite']==1){$Pdp=0;}
										}
									?>
									<br>
									<table width="95%" cellpadding="0" cellspacing="0" align="center" style="border:1px solid black;<?php if($Pdp==0){echo "display:none;";} ?>">
										<?php 
											$planPrevention="";
											$dateValidite="";
											$laDateValidite=date('0001-01-01');
											
											if($nbResultaMoisPresta>0){
												$req="SELECT Id, RefPdp,DateValidite
													FROM moris_pdp 
													WHERE Id_Prestation=".$PrestationSelect." 
													AND Annee=".$annee." 
													AND Mois=".$mois." ";
											}
											else{
												$req="SELECT Id, RefPdp,DateValidite
													FROM moris_pdp 
													WHERE Id_Prestation=".$PrestationSelect." 
													AND Annee=".$annee_M_1." 
													AND Mois=".$mois_M_1." ";
											}
											$resultPdp=mysqli_query($bdd,$req);
											$nbResultaPdp=mysqli_num_rows($resultPdp);
											if($nbResultaPdp>0){
												$rowPdp=mysqli_fetch_array($resultPdp);
												$planPrevention=$rowPdp['RefPdp'];
												$dateValidite=AfficheDateFR($rowPdp['DateValidite']);
												$laDateValidite=$rowPdp['DateValidite'];
											}	
										?>
										<tr>
											<td class="Libelle" style="border:1px solid black;" align="center" width="30%"><?php if($_SESSION['Langue']=="EN"){echo "Prevention plan";}else{echo "Plan de prévention";} ?></td>
											<td style="border:1px solid black;" width="70%">
												<input type="texte" style="text-align:center;" name="planPrevention" id="planPrevention" size="40" value="<?php echo $planPrevention; ?>">
											</td>
										</tr>
										<tr>
											<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Validity date";}else{echo "Date de validité";} ?></td>
											<td style="border:1px solid black;">
												<input type="date" name="dateValidite" id="dateValidite" style="text-align:center;<?php if($laDateValidite<date('Y-m-d')){echo "background-color:#f33535;";}elseif($laDateValidite<date('Y-m-d',strtotime(date('Y-m-d')." +1 month"))){echo "background-color:#f6a46a;";} ?>" size="10" value="<?php echo $dateValidite; ?>">
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr><td align="center">
							<?php 
								if($nbResultaMoisPresta>0){
									if($LigneMoisPrestation['Verouillage']==1){
										echo "<img width='30px' src='../../Images/Cadenas.png' border='0' />";
									}
								}
							?>
							</td></tr>
							<tr><td height="4"></td></tr>
						</table>
					</td>
					<td width="50%" class="Libelle" height="20px;" style="font-size:15px;">
						&nbsp;&nbsp;&nbsp;<input type="checkbox" class="PasActivite" id="PasActivite" name="PasActivite" <?php if($nbResultaMoisPresta>0){if($LigneMoisPrestation['PasActivite']==1){echo "checked";}}?> style="text-align:center;"><?php if($_SESSION['Langue']=="EN"){echo "No activity this month";}else{echo "Pas d'activité ce mois-ci";} ?>
					</td>
					<td width="25%"></td>
				</tr>
				<tr>
					<td width="50%" valign="top"  height="530px;">
						<?php 
							$req="SELECT FR,EN
							FROM moris_aideparagraphe
							WHERE moris_aideparagraphe.NomParagraphe='BESOIN STAFFING' ";
							$resultAide=mysqli_query($bdd,$req);
							$LigneAide=mysqli_fetch_array($resultAide);
						?>
						<table <?php if($Ligne['ChargeADesactive']==1){echo "style='display:none'";} ?> class="TableCompetences" width="99%" style="height: 100%;" cellpadding="0" cellspacing="0">
							<tr>
								<td class="Libelle" height="5%" style="font-size:15px;"><?php if($_SESSION['Langue']=="EN"){echo "CHARGE / CAPACITY";}else{echo "CHARGE / CAPACITÉ";} ?></td>
								<td style="cursor:pointer;" align="right"><div onclick="AfficheAide('Besoins')"><img id="Besoins" width="30px" src='../../Images/Aide2.png' border='0' /></div></td>
							</tr>
							<tr class="Besoins" style="display:none;"><td height="4"></td></tr>
							<tr class="Besoins" style="display:none;"><td bgcolor="#d5f2ff" style="border-top:2px dotted #0b6acb;" colspan="2" height="4"></td></tr>
							<tr class="Besoins" style="display:none;">
								<td colspan="2" bgcolor="#d5f2ff" style="color:#000000;">
								<?php if($_SESSION['Langue']=="EN"){echo nl2br(str_replace("\\","",stripslashes($LigneAide['EN'])));}else{echo nl2br(str_replace("\\","",stripslashes($LigneAide['FR'])));} ?>
								</td>
							</tr>
							<tr><td style="border-bottom:2px solid #0b6acb;" colspan="2" height="4"></td></tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td align='center' colspan="2" <?php if($annee."_".$mois>"2022_09"){echo "style='display:none;'";} ?>>
									<div><input onclick="Reporter('Charge')" class="Bouton" type="button" value="Report M-1" /></div>
									&nbsp;
								</td>
							</tr>
							<tr>
								<td align='center' colspan="2" <?php if($annee."_".$mois<="2022_09"){echo "style='display:none;'";} ?>>
									<div><input onclick="Reporter('Charge2')" class="Bouton" type="button" value="Report M-1 -> M" />
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<input onclick="Reporter('Charge3')" class="Bouton" type="button" value="Charge/Capa Iso" />
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<input onclick="EditFamille(<?php if($nbResultaMoisPrestaM1>0){echo $LigneMoisPrestationM1['Id'];}else{echo 0;}?>)" class="Bouton" type="button" value="<?php if($_SESSION['Langue']=="EN"){echo "Families";}else{echo "Familles";} ?>" /></div>
								</td>
							</tr>
							<tr>
								<td align='center' height="8px;" colspan="2">
								</td>
							</tr>
							<tr>
								<td height="95%" colspan="2" valign="top">
									<table width="90%" cellpadding="0" cellspacing="0" align="center" <?php if($annee."_".$mois<="2022_09"){echo "style='display:none;'";} ?> >
										<tr>
											<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Charge/capacity";}else{echo "Charge/Capa";} ?></td>
											<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Family";}else{echo "Famille";} ?></td>
											<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Resource";}else{echo "Ressource";} ?></td>
											<td class="Libelle" style="border:1px solid black;background-color:#dddbd5;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "M-1";}else{echo "M-1";} ?></td>
											<td class="Libelle" style="border:1px solid black;background-color:#e9dda9;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "M";}else{echo "M";} ?></td>
											<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "M+1";}else{echo "M+1";} ?></td>
											<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "M+2";}else{echo "M+2";} ?></td>
											<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "M+3";}else{echo "M+3";} ?></td>
											<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "M+4";}else{echo "M+4";} ?></td>
											<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "M+5";}else{echo "M+5";} ?></td>
											<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "M+6";}else{echo "M+6";} ?></td>
										</tr>
										<?php 
											$M_1="";
											$M="";
											$M1="";
											$M2="";
											$M3="";
											$M4="";
											$M5="";
											$M6="";
											$M1old="";
											$M2old="";
											$M3old="";
											$M4old="";
											$M5old="";
											$M6old="";
											
											$CapaM_1="";
											$CapaM="";
											$CapaM1="";
											$CapaM2="";
											$CapaM3="";
											$CapaM4="";
											$CapaM5="";
											$CapaM6="";
											$CapaM1old="";
											$CapaM2old="";
											$CapaM3old="";
											$CapaM4old="";
											$CapaM5old="";
											$CapaM6old="";
											$visibleInterne="style='display:none'";
											if($annee."_".$mois>"2022_09"){
												if($nbResultaMoisPrestaM1>0){
													if($annee."_".$mois=="2022_10"){
														$M_1=$LigneMoisPrestationM1['InterneCurrent'];
														$M1old=$LigneMoisPrestationM1['M1'];
														$M2old=$LigneMoisPrestationM1['M2'];
														$M3old=$LigneMoisPrestationM1['M3'];
														$M4old=$LigneMoisPrestationM1['M4'];
														$M5old=$LigneMoisPrestationM1['M5'];
														$M6old=$LigneMoisPrestationM1['M6'];
														
														$CapaM_1=$LigneMoisPrestationM1['InterneCurrent'];
														$CapaM1old=$LigneMoisPrestationM1['InterneCurrent'];
														$CapaM2old=$LigneMoisPrestationM1['InterneCurrent'];
														$CapaM3old=$LigneMoisPrestationM1['InterneCurrent'];
														$CapaM4old=$LigneMoisPrestationM1['InterneCurrent'];
														$CapaM5old=$LigneMoisPrestationM1['InterneCurrent'];
														$CapaM6old=$LigneMoisPrestationM1['InterneCurrent'];
													}
													else{
														$req="SELECT M,M1,M2,M3,M4,M5,M6,CapaM,CapaM1,CapaM2,CapaM3,CapaM4,CapaM5,CapaM6
															FROM moris_moisprestation_famille 
															WHERE Externe=0 
															AND Id_Famille=0
															AND Id_MoisPrestation=".$LigneMoisPrestationM1['Id']." ";
														$resultFamilleMois=mysqli_query($bdd,$req);
														$nbFamilleMois=mysqli_num_rows($resultFamilleMois);
														if($nbFamilleMois>0){
															$LigneFamilleMois=mysqli_fetch_array($resultFamilleMois);
															$M_1=$LigneFamilleMois['M'];
															$M1old=$LigneFamilleMois['M1'];
															$M2old=$LigneFamilleMois['M2'];
															$M3old=$LigneFamilleMois['M3'];
															$M4old=$LigneFamilleMois['M4'];
															$M5old=$LigneFamilleMois['M5'];
															$M6old=$LigneFamilleMois['M6'];
															
															$CapaM_1=$LigneFamilleMois['CapaM'];
															$CapaM1old=$LigneFamilleMois['CapaM1'];
															$CapaM2old=$LigneFamilleMois['CapaM2'];
															$CapaM3old=$LigneFamilleMois['CapaM3'];
															$CapaM4old=$LigneFamilleMois['CapaM4'];
															$CapaM5old=$LigneFamilleMois['CapaM5'];
															$CapaM6old=$LigneFamilleMois['CapaM6'];
														}
													}
												}
												if($nbResultaMoisPresta>0){
													$req="SELECT M,M1,M2,M3,M4,M5,M6,CapaM,CapaM1,CapaM2,CapaM3,CapaM4,CapaM5,CapaM6
														FROM moris_moisprestation_famille 
														WHERE Externe=0 
														AND Id_Famille=0
														AND Id_MoisPrestation=".$LigneMoisPrestation['Id']." ";
													$resultFamilleMois=mysqli_query($bdd,$req);
													$nbFamilleMois=mysqli_num_rows($resultFamilleMois);
													if($nbFamilleMois>0){
														$LigneFamilleMois=mysqli_fetch_array($resultFamilleMois);
														$M=$LigneFamilleMois['M'];
														$M1=$LigneFamilleMois['M1'];
														$M2=$LigneFamilleMois['M2'];
														$M3=$LigneFamilleMois['M3'];
														$M4=$LigneFamilleMois['M4'];
														$M5=$LigneFamilleMois['M5'];
														$M6=$LigneFamilleMois['M6'];
														
														$CapaM=$LigneFamilleMois['CapaM'];
														$CapaM1=$LigneFamilleMois['CapaM1'];
														$CapaM2=$LigneFamilleMois['CapaM2'];
														$CapaM3=$LigneFamilleMois['CapaM3'];
														$CapaM4=$LigneFamilleMois['CapaM4'];
														$CapaM5=$LigneFamilleMois['CapaM5'];
														$CapaM6=$LigneFamilleMois['CapaM6'];
													}
												}
												
												if(unNombreSinon0($M_1)>0 || unNombreSinon0($CapaM_1)>0 
												|| (unNombreSinon0($M)+unNombreSinon0($M1)+unNombreSinon0($M2)+unNombreSinon0($M3)
													+unNombreSinon0($M4)+unNombreSinon0($M5)+unNombreSinon0($M6)
													+unNombreSinon0($CapaM)+unNombreSinon0($CapaM1)+unNombreSinon0($CapaM2)+unNombreSinon0($CapaM3)
													+unNombreSinon0($CapaM4)+unNombreSinon0($CapaM5)+unNombreSinon0($CapaM5))>0){
														$visibleInterne="";
												}
											}
										?>
										<tr id="interneIndefini" <?php echo $visibleInterne; ?> bgcolor="#f5e1d5" >
											<td class="Libelle" style="border:1px solid black;" align="center">
												<?php if($_SESSION['Langue']=="EN"){echo "Charge";}else{echo "Charge";} ?>
											</td>
											<td class="Libelle" style="border:1px solid black;" align="center">
												<?php if($_SESSION['Langue']=="EN"){echo "Indefinite";}else{echo "Indéfini";} ?>
											</td>
											<td class="Libelle" style="border:1px solid black;" align="center">
												<?php if($_SESSION['Langue']=="EN"){echo "Internal";}else{echo "Interne";} ?>
											</td>
											<td style="border:1px solid black;background-color:#dddbd5;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="interneIndefiniM_1" id="interneIndefiniM_1" disabled="disabled" size="3" style="text-align:center;background-color:#dddbd5;" value="<?php echo $M_1;?>">
											</td>
											<td style="border:1px solid black;background-color:#e9dda9;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="interneIndefiniM" id="interneIndefiniM" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#e9dda9;" value="<?php echo $M;?>">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="interneIndefiniM1" id="interneIndefiniM1" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#f5e1d5;" value="<?php echo $M1;?>">
												<input onKeyUp="nombre(this)" type="texte" name="interneIndefiniM1old" id="interneIndefiniM1old" size="3" style="text-align:center;display:none;" value="<?php echo $M1old;?>">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="interneIndefiniM2" id="interneIndefiniM2" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#f5e1d5;" value="<?php echo $M2;?>">
												<input onKeyUp="nombre(this)" type="texte" name="interneIndefiniM2old" id="interneIndefiniM2old" size="3" style="text-align:center;display:none;" value="<?php echo $M2old;?>">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="interneIndefiniM3" id="interneIndefiniM3" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#f5e1d5;" value="<?php echo $M3;?>">
												<input onKeyUp="nombre(this)" type="texte" name="interneIndefiniM3old" id="interneIndefiniM3old" size="3" style="text-align:center;display:none;" value="<?php echo $M3old;?>">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="interneIndefiniM4" id="interneIndefiniM4" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#f5e1d5;" value="<?php echo $M4;?>">
												<input onKeyUp="nombre(this)" type="texte" name="interneIndefiniM4old" id="interneIndefiniM4old" size="3" style="text-align:center;display:none;" value="<?php echo $M4old;?>">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="interneIndefiniM5" id="interneIndefiniM5" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#f5e1d5;" value="<?php echo $M5;?>">
												<input onKeyUp="nombre(this)" type="texte" name="interneIndefiniM5old" id="interneIndefiniM5old" size="3" style="text-align:center;display:none;" value="<?php echo $M5old;?>">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="interneIndefiniM6" id="interneIndefiniM6" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#f5e1d5;" value="<?php echo $M6;?>">
												<input onKeyUp="nombre(this)" type="texte" name="interneIndefiniM6old" id="interneIndefiniM6old" size="3" style="text-align:center;display:none;" value="<?php echo $M6old;?>">
											</td>
										</tr>
										<tr id="interneIndefiniCapa" <?php echo $visibleInterne; ?> bgcolor="#cbd4f3">
											<td class="Libelle" style="border:1px solid black;" align="center">
												<?php if($_SESSION['Langue']=="EN"){echo "Capacity";}else{echo "Capacité";} ?>
											</td>
											<td class="Libelle" style="border:1px solid black;" align="center">
												<?php if($_SESSION['Langue']=="EN"){echo "Indefinite";}else{echo "Indéfini";} ?>
											</td>
											<td class="Libelle" style="border:1px solid black;" align="center">
												<?php if($_SESSION['Langue']=="EN"){echo "Internal";}else{echo "Interne";} ?>
											</td>
											<td style="border:1px solid black;background-color:#dddbd5;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="interneIndefiniCapaM_1" id="interneIndefiniCapaM_1" disabled="disabled" size="3" style="text-align:center;background-color:#dddbd5;" value="<?php echo $CapaM_1;?>">
											</td>
											<td style="border:1px solid black;background-color:#e9dda9;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="interneIndefiniCapaM" id="interneIndefiniCapaM" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#e9dda9;" value="<?php echo $CapaM;?>">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="interneIndefiniCapaM1" id="interneIndefiniCapaM1" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#cbd4f3;" value="<?php echo $CapaM1;?>">
												<input onKeyUp="nombre(this)" type="texte" name="interneIndefiniCapaM1old" id="interneIndefiniCapaM1old" size="3" style="text-align:center;display:none;" value="<?php echo $CapaM1old;?>">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="interneIndefiniCapaM2" id="interneIndefiniCapaM2" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#cbd4f3;" value="<?php echo $CapaM2;?>">
												<input onKeyUp="nombre(this)" type="texte" name="interneIndefiniCapaM2old" id="interneIndefiniCapaM2old" size="3" style="text-align:center;display:none;" value="<?php echo $CapaM2old;?>">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="interneIndefiniCapaM3" id="interneIndefiniCapaM3" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#cbd4f3;" value="<?php echo $CapaM3;?>">
												<input onKeyUp="nombre(this)" type="texte" name="interneIndefiniCapaM3old" id="interneIndefiniCapaM3old" size="3" style="text-align:center;display:none;" value="<?php echo $CapaM3old;?>">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="interneIndefiniCapaM4" id="interneIndefiniCapaM4" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#cbd4f3;" value="<?php echo $CapaM4;?>">
												<input onKeyUp="nombre(this)" type="texte" name="interneIndefiniCapaM4old" id="interneIndefiniCapaM4old" size="3" style="text-align:center;display:none;" value="<?php echo $CapaM4old;?>">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="interneIndefiniCapaM5" id="interneIndefiniCapaM5" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#cbd4f3;" value="<?php echo $CapaM5;?>">
												<input onKeyUp="nombre(this)" type="texte" name="interneIndefiniCapaM5old" id="interneIndefiniCapaM5old" size="3" style="text-align:center;display:none;" value="<?php echo $CapaM5old;?>">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="interneIndefiniCapaM6" id="interneIndefiniCapaM6" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#cbd4f3;" value="<?php echo $CapaM6;?>">
												<input onKeyUp="nombre(this)" type="texte" name="interneIndefiniCapaM6old" id="interneIndefiniCapaM6old" size="3" style="text-align:center;display:none;" value="<?php echo $CapaM6old;?>">
											</td>
										</tr>
										<?php 
											$M_1="";
											$M="";
											$M1="";
											$M2="";
											$M3="";
											$M4="";
											$M5="";
											$M6="";
											$M1old="";
											$M2old="";
											$M3old="";
											$M4old="";
											$M5old="";
											$M6old="";
											
											$CapaM_1="";
											$CapaM="";
											$CapaM1="";
											$CapaM2="";
											$CapaM3="";
											$CapaM4="";
											$CapaM5="";
											$CapaM6="";
											$CapaM1old="";
											$CapaM2old="";
											$CapaM3old="";
											$CapaM4old="";
											$CapaM5old="";
											$CapaM6old="";
											$visibleExterne="style='display:none'";
											if($annee."_".$mois>"2022_09"){
												if($nbResultaMoisPrestaM1>0){
													if($annee."_".$mois=="2022_10"){
														$M_1=$LigneMoisPrestationM1['SubContractorCurrent'];
														
														$CapaM_1=$LigneMoisPrestationM1['SubContractorCurrent'];
													}
													else{
														$req="SELECT M,M1,M2,M3,M4,M5,M6,CapaM,CapaM1,CapaM2,CapaM3,CapaM4,CapaM5,CapaM6 
															FROM moris_moisprestation_famille 
															WHERE Externe=1
															AND Id_Famille=0
															AND Id_MoisPrestation=".$LigneMoisPrestationM1['Id']." ";
														$resultFamilleMois=mysqli_query($bdd,$req);
														$nbFamilleMois=mysqli_num_rows($resultFamilleMois);
														if($nbFamilleMois>0){
															$LigneFamilleMois=mysqli_fetch_array($resultFamilleMois);
															$M_1=$LigneFamilleMois['M'];
															$M1old=$LigneFamilleMois['M1'];
															$M2old=$LigneFamilleMois['M2'];
															$M3old=$LigneFamilleMois['M3'];
															$M4old=$LigneFamilleMois['M4'];
															$M5old=$LigneFamilleMois['M5'];
															$M6old=$LigneFamilleMois['M6'];
															
															$CapaM_1=$LigneFamilleMois['CapaM'];
															$CapaM1old=$LigneFamilleMois['CapaM1'];
															$CapaM2old=$LigneFamilleMois['CapaM2'];
															$CapaM3old=$LigneFamilleMois['CapaM3'];
															$CapaM4old=$LigneFamilleMois['CapaM4'];
															$CapaM5old=$LigneFamilleMois['CapaM5'];
															$CapaM6old=$LigneFamilleMois['CapaM6'];
														}
													}
												}
												if($nbResultaMoisPresta>0){
													$req="SELECT M,M1,M2,M3,M4,M5,M6,CapaM,CapaM1,CapaM2,CapaM3,CapaM4,CapaM5,CapaM6 
														FROM moris_moisprestation_famille 
														WHERE Externe=1
														AND Id_Famille=0
														AND Id_MoisPrestation=".$LigneMoisPrestation['Id']." ";
													$resultFamilleMois=mysqli_query($bdd,$req);
													$nbFamilleMois=mysqli_num_rows($resultFamilleMois);
													if($nbFamilleMois>0){
														$LigneFamilleMois=mysqli_fetch_array($resultFamilleMois);
														$M=$LigneFamilleMois['M'];
														$M1=$LigneFamilleMois['M1'];
														$M2=$LigneFamilleMois['M2'];
														$M3=$LigneFamilleMois['M3'];
														$M4=$LigneFamilleMois['M4'];
														$M5=$LigneFamilleMois['M5'];
														$M6=$LigneFamilleMois['M6'];
														
														$CapaM=$LigneFamilleMois['CapaM'];
														$CapaM1=$LigneFamilleMois['CapaM1'];
														$CapaM2=$LigneFamilleMois['CapaM2'];
														$CapaM3=$LigneFamilleMois['CapaM3'];
														$CapaM4=$LigneFamilleMois['CapaM4'];
														$CapaM5=$LigneFamilleMois['CapaM5'];
														$CapaM6=$LigneFamilleMois['CapaM6'];
													}
												}
												if(unNombreSinon0($M_1)>0 || unNombreSinon0($CapaM_1)>0 
												|| (unNombreSinon0($M)+unNombreSinon0($M1)+unNombreSinon0($M2)+unNombreSinon0($M3)
													+unNombreSinon0($M4)+unNombreSinon0($M5)+unNombreSinon0($M6)
													+unNombreSinon0($CapaM)+unNombreSinon0($CapaM1)+unNombreSinon0($CapaM2)+unNombreSinon0($CapaM3)
													+unNombreSinon0($CapaM4)+unNombreSinon0($CapaM5)+unNombreSinon0($CapaM5))>0){
														$visibleExterne="";
												}
											}
										?>
										<tr id="externeIndefini" <?php echo $visibleExterne; ?> bgcolor="#f5e1d5">
											<td class="Libelle" style="border:1px solid black;" align="center">
												<?php if($_SESSION['Langue']=="EN"){echo "Charge";}else{echo "Charge";} ?>
											</td>
											<td class="Libelle" style="border:1px solid black;" align="center">
												<?php if($_SESSION['Langue']=="EN"){echo "Indefinite";}else{echo "Indéfini";} ?>
											</td>
											<td class="Libelle" style="border:1px solid black;" align="center">
												<?php if($_SESSION['Langue']=="EN"){echo "External";}else{echo "Externe";} ?>
											</td>
											<td style="border:1px solid black;background-color:#dddbd5;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="externeIndefiniM_1" id="externeIndefiniM_1" disabled="disabled" size="3" style="text-align:center;background-color:#dddbd5;" value="<?php echo $M_1;?>">
											</td>
											<td style="border:1px solid black;background-color:#e9dda9;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="externeIndefiniM" id="externeIndefiniM" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#e9dda9;" value="<?php echo $M;?>">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="externeIndefiniM1" id="externeIndefiniM1" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#f5e1d5;" value="<?php echo $M1;?>">
												<input onKeyUp="nombre(this)" type="texte" name="externeIndefiniM1old" id="externeIndefiniM1old" size="3" style="text-align:center;display:none;" value="<?php echo $M1old;?>">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="externeIndefiniM2" id="externeIndefiniM2" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#f5e1d5;" value="<?php echo $M2;?>">
												<input onKeyUp="nombre(this)" type="texte" name="externeIndefiniM2old" id="externeIndefiniM2old" size="3" style="text-align:center;display:none;" value="<?php echo $M2old;?>">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="externeIndefiniM3" id="externeIndefiniM3" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#f5e1d5;" value="<?php echo $M3;?>">
												<input onKeyUp="nombre(this)" type="texte" name="externeIndefiniM3old" id="externeIndefiniM3old" size="3" style="text-align:center;display:none;" value="<?php echo $M3old;?>">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="externeIndefiniM4" id="externeIndefiniM4" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#f5e1d5;" value="<?php echo $M4;?>">
												<input onKeyUp="nombre(this)" type="texte" name="externeIndefiniM4old" id="externeIndefiniM4old" size="3" style="text-align:center;display:none;" value="<?php echo $M4old;?>">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="externeIndefiniM5" id="externeIndefiniM5" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#f5e1d5;" value="<?php echo $M5;?>">
												<input onKeyUp="nombre(this)" type="texte" name="externeIndefiniM5old" id="externeIndefiniM5old" size="3" style="text-align:center;display:none;" value="<?php echo $M5old;?>">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="externeIndefiniM6" id="externeIndefiniM6" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#f5e1d5;" value="<?php echo $M6;?>">
												<input onKeyUp="nombre(this)" type="texte" name="externeIndefiniM6old" id="externeIndefiniM6old" size="3" style="text-align:center;display:none;" value="<?php echo $M6old;?>">
											</td>
										</tr>
										<tr id="externeIndefiniCapa" <?php echo $visibleExterne; ?> bgcolor="#cbd4f3">
											<td class="Libelle" style="border:1px solid black;" align="center">
												<?php if($_SESSION['Langue']=="EN"){echo "Capacity";}else{echo "Capacité";} ?>
											</td>
											<td class="Libelle" style="border:1px solid black;" align="center">
												<?php if($_SESSION['Langue']=="EN"){echo "Indefinite";}else{echo "Indéfini";} ?>
											</td>
											<td class="Libelle" style="border:1px solid black;" align="center">
												<?php if($_SESSION['Langue']=="EN"){echo "External";}else{echo "Externe";} ?>
											</td>
											<td style="border:1px solid black;background-color:#dddbd5;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="externeIndefiniCapaM_1" id="externeIndefiniCapaM_1" disabled="disabled" size="3" style="text-align:center;background-color:#dddbd5;" value="<?php echo $CapaM_1;?>">
											</td>
											<td style="border:1px solid black;background-color:#e9dda9;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="externeIndefiniCapaM" id="externeIndefiniCapaM" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#e9dda9;" value="<?php echo $CapaM;?>">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="externeIndefiniCapaM1" id="externeIndefiniCapaM1" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#cbd4f3;" value="<?php echo $CapaM1;?>">
												<input onKeyUp="nombre(this)" type="texte" name="externeIndefiniCapaM1old" id="externeIndefiniCapaM1old" size="3" style="text-align:center;display:none;" value="<?php echo $CapaM1old;?>">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="externeIndefiniCapaM2" id="externeIndefiniCapaM2" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#cbd4f3;" value="<?php echo $CapaM2;?>">
												<input onKeyUp="nombre(this)" type="texte" name="externeIndefiniCapaM2old" id="externeIndefiniCapaM2old" size="3" style="text-align:center;display:none;" value="<?php echo $CapaM2old;?>">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="externeIndefiniCapaM3" id="externeIndefiniCapaM3" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#cbd4f3;" value="<?php echo $CapaM3;?>">
												<input onKeyUp="nombre(this)" type="texte" name="externeIndefiniCapaM3old" id="externeIndefiniCapaM3old" size="3" style="text-align:center;display:none;" value="<?php echo $CapaM3old;?>">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="externeIndefiniCapaM4" id="externeIndefiniCapaM4" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#cbd4f3;" value="<?php echo $CapaM4;?>">
												<input onKeyUp="nombre(this)" type="texte" name="externeIndefiniCapaM4old" id="externeIndefiniCapaM4old" size="3" style="text-align:center;display:none;" value="<?php echo $CapaM4old;?>">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="externeIndefiniCapaM5" id="externeIndefiniCapaM5" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#cbd4f3;" value="<?php echo $CapaM5;?>">
												<input onKeyUp="nombre(this)" type="texte" name="externeIndefiniCapaM5old" id="externeIndefiniCapaM5old" size="3" style="text-align:center;display:none;" value="<?php echo $CapaM5old;?>">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="externeIndefiniCapaM6" id="externeIndefiniCapaM6" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#cbd4f3;" value="<?php echo $CapaM6;?>">
												<input onKeyUp="nombre(this)" type="texte" name="externeIndefiniCapaM6old" id="externeIndefiniCapaM6old" size="3" style="text-align:center;display:none;" value="<?php echo $CapaM6old;?>">
											</td>
										</tr>
										<?php 
											$req="SELECT Id, Libelle 
												FROM moris_famille 
												WHERE Suppr=0 ";
											if($nbResultaMoisPrestaM1>0){
												$req.="OR Id IN (SELECT Id_Famille FROM moris_moisprestation_famille WHERE Id_MoisPrestation=".$LigneMoisPrestationM1['Id']." )";
											}
											$req.="ORDER BY Libelle";
											$resultFamille=mysqli_query($bdd,$req);
											$nbFamille=mysqli_num_rows($resultFamille);
											if($nbFamille>0){
												while($rowFamille=mysqli_fetch_array($resultFamille)){
													$M_1="";
													$M="";
													$M1="";
													$M2="";
													$M3="";
													$M4="";
													$M5="";
													$M6="";
													$M1old="";
													$M2old="";
													$M3old="";
													$M4old="";
													$M5old="";
													$M6old="";
													
													$CapaM_1="";
													$CapaM="";
													$CapaM1="";
													$CapaM2="";
													$CapaM3="";
													$CapaM4="";
													$CapaM5="";
													$CapaM6="";
													$CapaM1old="";
													$CapaM2old="";
													$CapaM3old="";
													$CapaM4old="";
													$CapaM5old="";
													$CapaM6old="";
													
													$eM_1="";
													$eM="";
													$eM1="";
													$eM2="";
													$eM3="";
													$eM4="";
													$eM5="";
													$eM6="";
													$eM1old="";
													$eM2old="";
													$eM3old="";
													$eM4old="";
													$eM5old="";
													$eM6old="";
													
													$eCapaM_1="";
													$eCapaM="";
													$eCapaM1="";
													$eCapaM2="";
													$eCapaM3="";
													$eCapaM4="";
													$eCapaM5="";
													$eCapaM6="";
													$eCapaM1old="";
													$eCapaM2old="";
													$eCapaM3old="";
													$eCapaM4old="";
													$eCapaM5old="";
													$eCapaM6old="";

													
													$visibleInterne="style='display:none'";
													$visibleExterne="style='display:none'";
													if($annee."_".$mois>"2022_09"){
														if($nbResultaMoisPresta>0){
															$req="SELECT M,M1,M2,M3,M4,M5,M6,CapaM,CapaM1,CapaM2,CapaM3,CapaM4,CapaM5,CapaM6  
																FROM moris_moisprestation_famille 
																WHERE Externe=0 
																AND Id_Famille=".$rowFamille['Id']." 
																AND Id_MoisPrestation=".$LigneMoisPrestation['Id']." ";
															$resultFamilleMois=mysqli_query($bdd,$req);
															$nbFamilleMois=mysqli_num_rows($resultFamilleMois);
															if($nbFamilleMois>0){
																$LigneFamilleMois=mysqli_fetch_array($resultFamilleMois);
																$M=$LigneFamilleMois['M'];
																$M1=$LigneFamilleMois['M1'];
																$M2=$LigneFamilleMois['M2'];
																$M3=$LigneFamilleMois['M3'];
																$M4=$LigneFamilleMois['M4'];
																$M5=$LigneFamilleMois['M5'];
																$M6=$LigneFamilleMois['M6'];
																
																$CapaM=$LigneFamilleMois['CapaM'];
																$CapaM1=$LigneFamilleMois['CapaM1'];
																$CapaM2=$LigneFamilleMois['CapaM2'];
																$CapaM3=$LigneFamilleMois['CapaM3'];
																$CapaM4=$LigneFamilleMois['CapaM4'];
																$CapaM5=$LigneFamilleMois['CapaM5'];
																$CapaM6=$LigneFamilleMois['CapaM6'];
															}
															$req="SELECT M,M1,M2,M3,M4,M5,M6,CapaM,CapaM1,CapaM2,CapaM3,CapaM4,CapaM5,CapaM6  
																FROM moris_moisprestation_famille 
																WHERE Externe=1
																AND Id_Famille=".$rowFamille['Id']." 
																AND Id_MoisPrestation=".$LigneMoisPrestation['Id']." ";
															$resultFamilleMois=mysqli_query($bdd,$req);
															$nbFamilleMois=mysqli_num_rows($resultFamilleMois);
															if($nbFamilleMois>0){
																$LigneFamilleMois=mysqli_fetch_array($resultFamilleMois);
																$eM=$LigneFamilleMois['M'];
																$eM1=$LigneFamilleMois['M1'];
																$eM2=$LigneFamilleMois['M2'];
																$eM3=$LigneFamilleMois['M3'];
																$eM4=$LigneFamilleMois['M4'];
																$eM5=$LigneFamilleMois['M5'];
																$eM6=$LigneFamilleMois['M6'];
																
																$eCapaM=$LigneFamilleMois['CapaM'];
																$eCapaM1=$LigneFamilleMois['CapaM1'];
																$eCapaM2=$LigneFamilleMois['CapaM2'];
																$eCapaM3=$LigneFamilleMois['CapaM3'];
																$eCapaM4=$LigneFamilleMois['CapaM4'];
																$eCapaM5=$LigneFamilleMois['CapaM5'];
																$eCapaM6=$LigneFamilleMois['CapaM6'];
															}
														}
														if($nbResultaMoisPrestaM1>0){
															$req="SELECT M,M1,M2,M3,M4,M5,M6,CapaM,CapaM1,CapaM2,CapaM3,CapaM4,CapaM5,CapaM6  
																FROM moris_moisprestation_famille 
																WHERE Externe=0 
																AND Id_Famille=".$rowFamille['Id']." 
																AND Id_MoisPrestation=".$LigneMoisPrestationM1['Id']." ";
															$resultFamilleMois=mysqli_query($bdd,$req);
															$nbFamilleMois=mysqli_num_rows($resultFamilleMois);
															if($nbFamilleMois>0){
																$LigneFamilleMois=mysqli_fetch_array($resultFamilleMois);
																$M_1=$LigneFamilleMois['M'];
																$M1old=$LigneFamilleMois['M1'];
																$M2old=$LigneFamilleMois['M2'];
																$M3old=$LigneFamilleMois['M3'];
																$M4old=$LigneFamilleMois['M4'];
																$M5old=$LigneFamilleMois['M5'];
																$M6old=$LigneFamilleMois['M6'];
																
																$CapaM_1=$LigneFamilleMois['CapaM'];
																$CapaM1old=$LigneFamilleMois['CapaM1'];
																$CapaM2old=$LigneFamilleMois['CapaM2'];
																$CapaM3old=$LigneFamilleMois['CapaM3'];
																$CapaM4old=$LigneFamilleMois['CapaM4'];
																$CapaM5old=$LigneFamilleMois['CapaM5'];
																$CapaM6old=$LigneFamilleMois['CapaM6'];
																
																
															}
															$req="SELECT M,M1,M2,M3,M4,M5,M6,CapaM,CapaM1,CapaM2,CapaM3,CapaM4,CapaM5,CapaM6 
																FROM moris_moisprestation_famille 
																WHERE Externe=1
																AND Id_Famille=".$rowFamille['Id']." 
																AND Id_MoisPrestation=".$LigneMoisPrestationM1['Id']." ";
															$resultFamilleMois=mysqli_query($bdd,$req);
															$nbFamilleMois=mysqli_num_rows($resultFamilleMois);
															if($nbFamilleMois>0){
																$LigneFamilleMois=mysqli_fetch_array($resultFamilleMois);
																$eM_1=$LigneFamilleMois['M'];
																$eM1old=$LigneFamilleMois['M1'];
																$eM2old=$LigneFamilleMois['M2'];
																$eM3old=$LigneFamilleMois['M3'];
																$eM4old=$LigneFamilleMois['M4'];
																$eM5old=$LigneFamilleMois['M5'];
																$eM6old=$LigneFamilleMois['M6'];
																
																$eCapaM_1=$LigneFamilleMois['CapaM'];
																$eCapaM1old=$LigneFamilleMois['CapaM1'];
																$eCapaM2old=$LigneFamilleMois['CapaM2'];
																$eCapaM3old=$LigneFamilleMois['CapaM3'];
																$eCapaM4old=$LigneFamilleMois['CapaM4'];
																$eCapaM5old=$LigneFamilleMois['CapaM5'];
																$eCapaM6old=$LigneFamilleMois['CapaM6'];
															}
														}
													}
													if(unNombreSinon0($M_1)>0 || unNombreSinon0($CapaM_1)>0 
													|| (unNombreSinon0($M)+unNombreSinon0($M1)+unNombreSinon0($M2)+unNombreSinon0($M3)
														+unNombreSinon0($M4)+unNombreSinon0($M5)+unNombreSinon0($M6)
														+unNombreSinon0($CapaM)+unNombreSinon0($CapaM1)+unNombreSinon0($CapaM2)+unNombreSinon0($CapaM3)
														+unNombreSinon0($CapaM4)+unNombreSinon0($CapaM5)+unNombreSinon0($CapaM6))>0){
															$visibleInterne="";
													}
													if(unNombreSinon0($eM_1)>0 || unNombreSinon0($eCapaM_1)>0 
													|| (unNombreSinon0($eM)+unNombreSinon0($eM1)+unNombreSinon0($eM2)+unNombreSinon0($eM3)
														+unNombreSinon0($eM4)+unNombreSinon0($eM5)+unNombreSinon0($eM6)
														+unNombreSinon0($eCapaM)+unNombreSinon0($eCapaM1)+unNombreSinon0($eCapaM2)+unNombreSinon0($eCapaM3)
														+unNombreSinon0($eCapaM4)+unNombreSinon0($eCapaM5)+unNombreSinon0($eCapaM6))>0){
															$visibleExterne="";
													}
										?>
										<tr id="interne<?php echo $rowFamille['Id'];?>" class="interneExterne" <?php echo $visibleInterne; ?> bgcolor="#f5e1d5">
											<td class="Libelle" style="border:1px solid black;" align="center">
												<?php if($_SESSION['Langue']=="EN"){echo "Charge";}else{echo "Charge";} ?>
											</td>
											<td class="Libelle" style="border:1px solid black;" align="center">
												<?php echo $rowFamille['Libelle'];?>
											</td>
											<td class="Libelle" style="border:1px solid black;" align="center">
												<?php if($_SESSION['Langue']=="EN"){echo "Internal";}else{echo "Interne";} ?>
											</td>
											<td style="border:1px solid black;background-color:#dddbd5;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="interne<?php echo $rowFamille['Id'];?>M_1" disabled="disabled" id="interne<?php echo $rowFamille['Id'];?>M_1" size="3" style="text-align:center;background-color:#dddbd5;" value="<?php echo $M_1;?>">
											</td>
											<td style="border:1px solid black;background-color:#e9dda9;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="interne<?php echo $rowFamille['Id'];?>M" id="interne<?php echo $rowFamille['Id'];?>M" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#e9dda9;" value="<?php echo $M;?>">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="interne<?php echo $rowFamille['Id'];?>M1" id="interne<?php echo $rowFamille['Id'];?>M1" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#f5e1d5;" value="<?php echo $M1;?>">
												<input onKeyUp="nombre(this)" type="texte" name="interne<?php echo $rowFamille['Id'];?>M1old" id="interne<?php echo $rowFamille['Id'];?>M1old" size="3" style="text-align:center;display:none;" value="<?php echo $M1old;?>">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="interne<?php echo $rowFamille['Id'];?>M2" id="interne<?php echo $rowFamille['Id'];?>M2" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#f5e1d5;" value="<?php echo $M2;?>">
												<input onKeyUp="nombre(this)" type="texte" name="interne<?php echo $rowFamille['Id'];?>M2old" id="interne<?php echo $rowFamille['Id'];?>M2old" size="3" style="text-align:center;display:none;" value="<?php echo $M2old;?>">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="interne<?php echo $rowFamille['Id'];?>M3" id="interne<?php echo $rowFamille['Id'];?>M3" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#f5e1d5;" value="<?php echo $M3;?>">
												<input onKeyUp="nombre(this)" type="texte" name="interne<?php echo $rowFamille['Id'];?>M3old" id="interne<?php echo $rowFamille['Id'];?>M3old" size="3" style="text-align:center;display:none;" value="<?php echo $M3old;?>">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="interne<?php echo $rowFamille['Id'];?>M4" id="interne<?php echo $rowFamille['Id'];?>M4" onchange="CalculerTotalCharge()" size="3" style="text-align:center; background-color:#f5e1d5;" value="<?php echo $M4;?>">
												<input onKeyUp="nombre(this)" type="texte" name="interne<?php echo $rowFamille['Id'];?>M4old" id="interne<?php echo $rowFamille['Id'];?>M4old" size="3" style="text-align:center;display:none;" value="<?php echo $M4old;?>">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="interne<?php echo $rowFamille['Id'];?>M5" id="interne<?php echo $rowFamille['Id'];?>M5" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#f5e1d5;" value="<?php echo $M5;?>">
												<input onKeyUp="nombre(this)" type="texte" name="interne<?php echo $rowFamille['Id'];?>M5old" id="interne<?php echo $rowFamille['Id'];?>M5old" size="3" style="text-align:center;display:none;" value="<?php echo $M5old;?>">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="interne<?php echo $rowFamille['Id'];?>M6" id="interne<?php echo $rowFamille['Id'];?>M6" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#f5e1d5;" value="<?php echo $M6;?>">
												<input onKeyUp="nombre(this)" type="texte" name="interne<?php echo $rowFamille['Id'];?>M6old" id="interne<?php echo $rowFamille['Id'];?>M6old" size="3" style="text-align:center;display:none;" value="<?php echo $M6old;?>">
											</td>
										</tr>
										<tr id="interne<?php echo $rowFamille['Id'];?>Capa" class="interneExterne" <?php echo $visibleInterne; ?> bgcolor="#cbd4f3">
											<td class="Libelle" style="border:1px solid black;" align="center">
												<?php if($_SESSION['Langue']=="EN"){echo "Capacity";}else{echo "Capacité";} ?>
											</td>
											<td class="Libelle" style="border:1px solid black;" align="center">
												<?php echo $rowFamille['Libelle'];?>
											</td>
											<td class="Libelle" style="border:1px solid black;" align="center">
												<?php if($_SESSION['Langue']=="EN"){echo "Internal";}else{echo "Interne";} ?>
											</td>
											<td style="border:1px solid black;background-color:#dddbd5;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="interne<?php echo $rowFamille['Id'];?>CapaM_1" disabled="disabled" id="interne<?php echo $rowFamille['Id'];?>CapaM_1" size="3" style="text-align:center;background-color:#dddbd5;" value="<?php echo $CapaM_1;?>">
											</td>
											<td style="border:1px solid black;background-color:#e9dda9;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="interne<?php echo $rowFamille['Id'];?>CapaM" id="interne<?php echo $rowFamille['Id'];?>CapaM" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#e9dda9;" value="<?php echo $CapaM;?>">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="interne<?php echo $rowFamille['Id'];?>CapaM1" id="interne<?php echo $rowFamille['Id'];?>CapaM1" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#cbd4f3;" value="<?php echo $CapaM1;?>">
												<input onKeyUp="nombre(this)" type="texte" name="interne<?php echo $rowFamille['Id'];?>CapaM1old" id="interne<?php echo $rowFamille['Id'];?>CapaM1old" size="3" style="text-align:center;display:none;" value="<?php echo $CapaM1old;?>">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="interne<?php echo $rowFamille['Id'];?>CapaM2" id="interne<?php echo $rowFamille['Id'];?>CapaM2" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#cbd4f3;" value="<?php echo $CapaM2;?>">
												<input onKeyUp="nombre(this)" type="texte" name="interne<?php echo $rowFamille['Id'];?>CapaM2old" id="interne<?php echo $rowFamille['Id'];?>CapaM2old" size="3" style="text-align:center;display:none;" value="<?php echo $CapaM2old;?>">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="interne<?php echo $rowFamille['Id'];?>CapaM3" id="interne<?php echo $rowFamille['Id'];?>CapaM3" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#cbd4f3;" value="<?php echo $CapaM3;?>">
												<input onKeyUp="nombre(this)" type="texte" name="interne<?php echo $rowFamille['Id'];?>CapaM3old" id="interne<?php echo $rowFamille['Id'];?>CapaM3old" size="3" style="text-align:center;display:none;" value="<?php echo $CapaM3old;?>">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="interne<?php echo $rowFamille['Id'];?>CapaM4" id="interne<?php echo $rowFamille['Id'];?>CapaM4" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#cbd4f3;" value="<?php echo $CapaM4;?>">
												<input onKeyUp="nombre(this)" type="texte" name="interne<?php echo $rowFamille['Id'];?>CapaM4old" id="interne<?php echo $rowFamille['Id'];?>CapaM4old" size="3" style="text-align:center;display:none;" value="<?php echo $CapaM4old;?>">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="interne<?php echo $rowFamille['Id'];?>CapaM5" id="interne<?php echo $rowFamille['Id'];?>CapaM5" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#cbd4f3;" value="<?php echo $CapaM5;?>">
												<input onKeyUp="nombre(this)" type="texte" name="interne<?php echo $rowFamille['Id'];?>CapaM5old" id="interne<?php echo $rowFamille['Id'];?>CapaM5old" size="3" style="text-align:center;display:none;" value="<?php echo $CapaM5old;?>">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="interne<?php echo $rowFamille['Id'];?>CapaM6" id="interne<?php echo $rowFamille['Id'];?>CapaM6" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#cbd4f3;" value="<?php echo $CapaM6;?>">
												<input onKeyUp="nombre(this)" type="texte" name="interne<?php echo $rowFamille['Id'];?>CapaM6old" id="interne<?php echo $rowFamille['Id'];?>CapaM6old" size="3" style="text-align:center;display:none;" value="<?php echo $CapaM6old;?>">
											</td>
										</tr>
										<tr id="externe<?php echo $rowFamille['Id'];?>" class="interneExterne" <?php echo $visibleExterne; ?> bgcolor="#f5e1d5">
											<td class="Libelle" style="border:1px solid black;" align="center">
												<?php if($_SESSION['Langue']=="EN"){echo "Charge";}else{echo "Charge";} ?>
											</td>
											<td class="Libelle" style="border:1px solid black;" align="center">
												<?php echo $rowFamille['Libelle'];?>
											</td>
											<td class="Libelle" style="border:1px solid black;" align="center">
												<?php if($_SESSION['Langue']=="EN"){echo "External";}else{echo "Externe";} ?>
											</td>
											<td style="border:1px solid black;background-color:#dddbd5;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="externe<?php echo $rowFamille['Id'];?>M_1" disabled="disabled" id="externe<?php echo $rowFamille['Id'];?>M_1" size="3" style="text-align:center;background-color:#dddbd5;" value="<?php echo $eM_1;?>">
											</td>
											<td style="border:1px solid black;background-color:#e9dda9;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="externe<?php echo $rowFamille['Id'];?>M" id="externe<?php echo $rowFamille['Id'];?>M" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#e9dda9;" value="<?php echo $eM;?>">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="externe<?php echo $rowFamille['Id'];?>M1" id="externe<?php echo $rowFamille['Id'];?>M1" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#f5e1d5;" value="<?php echo $eM1;?>">
												<input onKeyUp="nombre(this)" type="texte" name="externe<?php echo $rowFamille['Id'];?>M1old" id="externe<?php echo $rowFamille['Id'];?>M1old" size="3" style="text-align:center;display:none;" value="<?php echo $eM1old;?>">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="externe<?php echo $rowFamille['Id'];?>M2" id="externe<?php echo $rowFamille['Id'];?>M2" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#f5e1d5;" value="<?php echo $eM2;?>">
												<input onKeyUp="nombre(this)" type="texte" name="externe<?php echo $rowFamille['Id'];?>M2old" id="externe<?php echo $rowFamille['Id'];?>M2old" size="3" style="text-align:center;display:none;" value="<?php echo $eM2old;?>">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="externe<?php echo $rowFamille['Id'];?>M3" id="externe<?php echo $rowFamille['Id'];?>M3" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#f5e1d5;" value="<?php echo $eM3;?>">
												<input onKeyUp="nombre(this)" type="texte" name="externe<?php echo $rowFamille['Id'];?>M3old" id="externe<?php echo $rowFamille['Id'];?>M3old" size="3" style="text-align:center;display:none;" value="<?php echo $eM3old;?>">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="externe<?php echo $rowFamille['Id'];?>M4" id="externe<?php echo $rowFamille['Id'];?>M4" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#f5e1d5;" value="<?php echo $eM4;?>">
												<input onKeyUp="nombre(this)" type="texte" name="externe<?php echo $rowFamille['Id'];?>M4old" id="externe<?php echo $rowFamille['Id'];?>M4old" size="3" style="text-align:center;display:none;" value="<?php echo $eM4old;?>">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="externe<?php echo $rowFamille['Id'];?>M5" id="externe<?php echo $rowFamille['Id'];?>M5" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#f5e1d5;" value="<?php echo $eM5;?>">
												<input onKeyUp="nombre(this)" type="texte" name="externe<?php echo $rowFamille['Id'];?>M5old" id="externe<?php echo $rowFamille['Id'];?>M5old" size="3" style="text-align:center;display:none;" value="<?php echo $eM5old;?>">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="externe<?php echo $rowFamille['Id'];?>M6" id="externe<?php echo $rowFamille['Id'];?>M6" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#f5e1d5;" value="<?php echo $eM6;?>">
												<input onKeyUp="nombre(this)" type="texte" name="externe<?php echo $rowFamille['Id'];?>M6old" id="externe<?php echo $rowFamille['Id'];?>M6old" size="3" style="text-align:center;display:none;" value="<?php echo $eM6old;?>">
											</td>
										</tr>
										<tr id="externe<?php echo $rowFamille['Id'];?>Capa" class="interneExterne" <?php echo $visibleExterne; ?> bgcolor="#cbd4f3">
											<td class="Libelle" style="border:1px solid black;" align="center">
												<?php if($_SESSION['Langue']=="EN"){echo "Capacity";}else{echo "Capacité";} ?>
											</td>
											<td class="Libelle" style="border:1px solid black;" align="center">
												<?php echo $rowFamille['Libelle'];?>
											</td>
											<td class="Libelle" style="border:1px solid black;" align="center">
												<?php if($_SESSION['Langue']=="EN"){echo "External";}else{echo "Externe";} ?>
											</td>
											<td style="border:1px solid black;background-color:#dddbd5;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="externe<?php echo $rowFamille['Id'];?>CapaM_1" disabled="disabled" id="externe<?php echo $rowFamille['Id'];?>CapaM_1" size="3" style="text-align:center;background-color:#dddbd5;" value="<?php echo $eCapaM_1;?>">
											</td>
											<td style="border:1px solid black;background-color:#e9dda9;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="externe<?php echo $rowFamille['Id'];?>CapaM" id="externe<?php echo $rowFamille['Id'];?>CapaM" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#e9dda9;" value="<?php echo $eCapaM;?>">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="externe<?php echo $rowFamille['Id'];?>CapaM1" id="externe<?php echo $rowFamille['Id'];?>CapaM1" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#cbd4f3;" value="<?php echo $eCapaM1;?>">
												<input onKeyUp="nombre(this)" type="texte" name="externe<?php echo $rowFamille['Id'];?>CapaM1old" id="externe<?php echo $rowFamille['Id'];?>CapaM1old" size="3" style="text-align:center;display:none;" value="<?php echo $eCapaM1old;?>">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="externe<?php echo $rowFamille['Id'];?>CapaM2" id="externe<?php echo $rowFamille['Id'];?>CapaM2" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#cbd4f3;" value="<?php echo $eCapaM2;?>">
												<input onKeyUp="nombre(this)" type="texte" name="externe<?php echo $rowFamille['Id'];?>CapaM2old" id="externe<?php echo $rowFamille['Id'];?>CapaM2old" size="3" style="text-align:center;display:none;" value="<?php echo $eCapaM2old;?>">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="externe<?php echo $rowFamille['Id'];?>CapaM3" id="externe<?php echo $rowFamille['Id'];?>CapaM3" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#cbd4f3;" value="<?php echo $eCapaM3;?>">
												<input onKeyUp="nombre(this)" type="texte" name="externe<?php echo $rowFamille['Id'];?>CapaM3old" id="externe<?php echo $rowFamille['Id'];?>CapaM3old" size="3" style="text-align:center;display:none;" value="<?php echo $eCapaM3old;?>">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="externe<?php echo $rowFamille['Id'];?>CapaM4" id="externe<?php echo $rowFamille['Id'];?>CapaM4" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#cbd4f3;" value="<?php echo $eCapaM4;?>">
												<input onKeyUp="nombre(this)" type="texte" name="externe<?php echo $rowFamille['Id'];?>CapaM4old" id="externe<?php echo $rowFamille['Id'];?>CapaM4old" size="3" style="text-align:center;display:none;" value="<?php echo $eCapaM4old;?>">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="externe<?php echo $rowFamille['Id'];?>CapaM5" id="externe<?php echo $rowFamille['Id'];?>CapaM5" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#cbd4f3;" value="<?php echo $eCapaM5;?>">
												<input onKeyUp="nombre(this)" type="texte" name="externe<?php echo $rowFamille['Id'];?>CapaM5old" id="externe<?php echo $rowFamille['Id'];?>CapaM5old" size="3" style="text-align:center;display:none;" value="<?php echo $eCapaM5old;?>">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="externe<?php echo $rowFamille['Id'];?>CapaM6" id="externe<?php echo $rowFamille['Id'];?>CapaM6" onchange="CalculerTotalCharge()" size="3" style="text-align:center;background-color:#cbd4f3;" value="<?php echo $eCapaM6;?>">
												<input onKeyUp="nombre(this)" type="texte" name="externe<?php echo $rowFamille['Id'];?>CapaM6old" id="externe<?php echo $rowFamille['Id'];?>CapaM6old" size="3" style="text-align:center;display:none;" value="<?php echo $eCapaM6old;?>">
											</td>
										</tr>
										<?php
												}
											}
										?>
										<tr id="STinterne" bgcolor="#f5e1d5">
											<td class="Libelle" style="border:1px solid black;" align="center">
												<?php if($_SESSION['Langue']=="EN"){echo "Charge";}else{echo "Charge";} ?>
											</td>
											<td class="Libelle" style="border:1px solid black;" align="center">
												<?php if($_SESSION['Langue']=="EN"){echo "Subtotal";}else{echo "Sous-total";} ?>
											</td>
											<td class="Libelle" style="border:1px solid black;" align="center">
												<?php if($_SESSION['Langue']=="EN"){echo "Internal";}else{echo "Interne";} ?>
											</td>
											<td style="border:1px solid black;background-color:#dddbd5;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="STinterneM_1" id="STinterneM_1" disabled="disabled" size="3" style="text-align:center;background-color:#dddbd5;" value="">
											</td>
											<td style="border:1px solid black;background-color:#e9dda9;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="STinterneM" id="STinterneM" disabled="disabled" size="3" style="text-align:center;background-color:#e9dda9;" value="">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="STinterneM1" id="STinterneM1" disabled="disabled" size="3" style="text-align:center;" value="">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="STinterneM2" id="STinterneM2" disabled="disabled" size="3" style="text-align:center;" value="">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="STinterneM3" id="STinterneM3" disabled="disabled" size="3" style="text-align:center;" value="">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="STinterneM4" id="STinterneM4" disabled="disabled" size="3" style="text-align:center;" value="">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="STinterneM5" id="STinterneM5" disabled="disabled" size="3" style="text-align:center;" value="">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="STinterneM6" id="STinterneM6" disabled="disabled" size="3" style="text-align:center;" value="">
											</td>
										</tr>
										<tr id="STinterneCapa" bgcolor="#cbd4f3">
											<td class="Libelle" style="border:1px solid black;" align="center">
												<?php if($_SESSION['Langue']=="EN"){echo "Capacity";}else{echo "Capacité";} ?>
											</td>
											<td class="Libelle" style="border:1px solid black;" align="center">
												<?php if($_SESSION['Langue']=="EN"){echo "Subtotal";}else{echo "Sous-total";} ?>
											</td>
											<td class="Libelle" style="border:1px solid black;" align="center">
												<?php if($_SESSION['Langue']=="EN"){echo "Internal";}else{echo "Interne";} ?>
											</td>
											<td style="border:1px solid black;background-color:#dddbd5;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="STinterneCapaM_1" id="STinterneCapaM_1" disabled="disabled" size="3" style="text-align:center;background-color:#dddbd5;" value="">
											</td>
											<td style="border:1px solid black;background-color:#e9dda9;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="STinterneCapaM" id="STinterneCapaM" disabled="disabled" size="3" style="text-align:center;background-color:#e9dda9;" value="">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="STinterneCapaM1" id="STinterneCapaM1" disabled="disabled" size="3" style="text-align:center;" value="">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="STinterneCapaM2" id="STinterneCapaM2" disabled="disabled" size="3" style="text-align:center;" value="">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="STinterneCapaM3" id="STinterneCapaM3" disabled="disabled" size="3" style="text-align:center;" value="">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="STinterneCapaM4" id="STinterneCapaM4" disabled="disabled" size="3" style="text-align:center;" value="">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="STinterneCapaM5" id="STinterneCapaM5" disabled="disabled" size="3" style="text-align:center;" value="">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="STinterneCapaM6" id="STinterneCapaM6" disabled="disabled" size="3" style="text-align:center;" value="">
											</td>
										</tr>
										<tr id="STexterne" bgcolor="#f5e1d5">
											<td class="Libelle" style="border:1px solid black;" align="center">
												<?php if($_SESSION['Langue']=="EN"){echo "Charge";}else{echo "Charge";} ?>
											</td>
											<td class="Libelle" style="border:1px solid black;" align="center">
												<?php if($_SESSION['Langue']=="EN"){echo "Subtotal";}else{echo "Sous-total";} ?>
											</td>
											<td class="Libelle" style="border:1px solid black;" align="center">
												<?php if($_SESSION['Langue']=="EN"){echo "External";}else{echo "Externe";} ?>
											</td>
											<td style="border:1px solid black;background-color:#dddbd5;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="STexterneM_1" id="STexterneM_1" disabled="disabled" size="3" style="text-align:center;background-color:#dddbd5;" value="">
											</td>
											<td style="border:1px solid black;background-color:#e9dda9;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="STexterneM" id="STexterneM" disabled="disabled" size="3" style="text-align:center;background-color:#e9dda9;" value="">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="STexterneM1" id="STexterneM1" disabled="disabled" size="3" style="text-align:center;" value="">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="STexterneM2" id="STexterneM2" disabled="disabled" size="3" style="text-align:center;" value="">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="STexterneM3" id="STexterneM3" disabled="disabled" size="3" style="text-align:center;" value="">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="STexterneM4" id="STexterneM4" disabled="disabled" size="3" style="text-align:center;" value="">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="STexterneM5" id="STexterneM5" disabled="disabled" size="3" style="text-align:center;" value="">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="STexterneM6" id="STexterneM6" disabled="disabled" size="3" style="text-align:center;" value="">
											</td>
										</tr>
										<tr id="STexterneCapa" bgcolor="#cbd4f3">
											<td class="Libelle" style="border:1px solid black;" align="center">
												<?php if($_SESSION['Langue']=="EN"){echo "Capacity";}else{echo "Capacité";} ?>
											</td>
											<td class="Libelle" style="border:1px solid black;" align="center">
												<?php if($_SESSION['Langue']=="EN"){echo "Subtotal";}else{echo "Sous-total";} ?>
											</td>
											<td class="Libelle" style="border:1px solid black;" align="center">
												<?php if($_SESSION['Langue']=="EN"){echo "External";}else{echo "Externe";} ?>
											</td>
											<td style="border:1px solid black;background-color:#dddbd5;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="STexterneCapaM_1" id="STexterneCapaM_1" disabled="disabled" size="3" style="text-align:center;background-color:#dddbd5;" value="">
											</td>
											<td style="border:1px solid black;background-color:#e9dda9;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="STexterneCapaM" id="STexterneCapaM" disabled="disabled" size="3" style="text-align:center;background-color:#e9dda9;" value="">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="STexterneCapaM1" id="STexterneCapaM1" disabled="disabled" size="3" style="text-align:center;" value="">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="STexterneCapaM2" id="STexterneCapaM2" disabled="disabled" size="3" style="text-align:center;" value="">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="STexterneCapaM3" id="STexterneCapaM3" disabled="disabled" size="3" style="text-align:center;" value="">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="STexterneCapaM4" id="STexterneCapaM4" disabled="disabled" size="3" style="text-align:center;" value="">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="STexterneCapaM5" id="STexterneCapaM5" disabled="disabled" size="3" style="text-align:center;" value="">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="STexterneCapaM6" id="STexterneCapaM6" disabled="disabled" size="3" style="text-align:center;" value="">
											</td>
										</tr>
										<tr bgcolor="#f5e1d5">
											<td class="Libelle" style="border:1px solid black;" align="center" colspan="3">
												<?php if($_SESSION['Langue']=="EN"){echo "Charge Total";}else{echo "Charge Total";} ?>
											</td>
											<td style="border:1px solid black;background-color:#dddbd5;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="TotalM_1" id="TotalM_1" disabled="disabled" size="3" style="text-align:center;background-color:#dddbd5;" value="">
											</td>
											<td style="border:1px solid black;background-color:#e9dda9;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="TotalM" id="TotalM" disabled="disabled" size="3" style="text-align:center;background-color:#e9dda9;" value="">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="TotalM1" id="TotalM1" disabled="disabled" size="3" style="text-align:center;" value="">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="TotalM2" id="TotalM2" disabled="disabled" size="3" style="text-align:center;" value="">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="TotalM3" id="TotalM3" disabled="disabled" size="3" style="text-align:center;" value="">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="TotalM4" id="TotalM4" disabled="disabled" size="3" style="text-align:center;" value="">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="TotalM5" id="TotalM5" disabled="disabled" size="3" style="text-align:center;" value="">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="TotalM6" id="TotalM6" disabled="disabled" size="3" style="text-align:center;" value="">
											</td>
										</tr>
										<tr bgcolor="#cbd4f3">
											<td class="Libelle" style="border:1px solid black;" align="center" colspan="3">
												<?php if($_SESSION['Langue']=="EN"){echo "Capacity Total";}else{echo "Capacité Total";} ?>
											</td>
											<td style="border:1px solid black;background-color:#dddbd5;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="TotalCapaM_1" id="TotalCapaM_1" disabled="disabled" size="3" style="text-align:center;background-color:#dddbd5;" value="">
											</td>
											<td style="border:1px solid black;background-color:#e9dda9;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="TotalCapaM" id="TotalCapaM" disabled="disabled" size="3" style="text-align:center;background-color:#e9dda9;" value="">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="TotalCapaM1" id="TotalCapaM1" disabled="disabled" size="3" style="text-align:center;" value="">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="TotalCapaM2" id="TotalCapaM2" disabled="disabled" size="3" style="text-align:center;" value="">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="TotalCapaM3" id="TotalCapaM3" disabled="disabled" size="3" style="text-align:center;" value="">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="TotalCapaM4" id="TotalCapaM4" disabled="disabled" size="3" style="text-align:center;" value="">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="TotalCapaM5" id="TotalCapaM5" disabled="disabled" size="3" style="text-align:center;" value="">
											</td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="TotalCapaM6" id="TotalCapaM6" disabled="disabled" size="3" style="text-align:center;" value="">
											</td>
										</tr>
									</table>
									<table width="90%" cellpadding="0" cellspacing="0" align="center" <?php if($annee."_".$mois>"2022_09"){echo "style='display:none;'";} ?> >
										<tr>
											<td rowspan="3"></td>
											<td class="Libelle" style="border:1px solid black;border-left:2px solid black;border-top:2px solid black;border-right:2px solid black;" align="center" colspan="7"><?php if($_SESSION['Langue']=="EN"){echo "STAFFING";}else{echo "STAFFING";} ?></td>
										</tr>
										<tr>
											<td class="Libelle" style="border:1px solid black;border-left:2px solid black;" align="center" rowspan="2"><?php if($_SESSION['Langue']=="EN"){echo "Current";}else{echo "Actuel";} ?></td>
											<td class="Libelle" style="border:1px solid black;border-right:2px solid black;" align="center" colspan="6"><?php if($_SESSION['Langue']=="EN"){echo "Forecast";}else{echo "Prévisionnel";} ?></td>
										</tr>
										<tr>
											<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "M+1";}else{echo "M+1";} ?></td>
											<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "M+2";}else{echo "M+2";} ?></td>
											<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "M+3";}else{echo "M+3";} ?></td>
											<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "M+4";}else{echo "M+4";} ?></td>
											<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "M+5";}else{echo "M+5";} ?></td>
											<td class="Libelle" style="border:1px solid black;border-right:2px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "M+6";}else{echo "M+6";} ?></td>
										</tr>
										<tr>
											<td class="Libelle" style="border:1px solid black;border-left:2px solid black;border-top:2px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Internal";}else{echo "Interne";} ?></td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="interneCurrent" id="interneCurrent" size="3" style="text-align:center;" value="<?php if($nbResultaMoisPresta>0){echo $LigneMoisPrestation['InterneCurrent'];}?>">
												<input onKeyUp="nombre(this)" type="texte" name="interneCurrentM1" id="interneCurrentM1" size="3" style="text-align:center;display:none;" value="<?php if($nbResultaMoisPrestaM1>0){echo $LigneMoisPrestationM1['InterneCurrent'];}?>">
												<input onKeyUp="nombre(this)" type="texte" name="sommeCurrent" id="sommeCurrent" size="3" style="text-align:center;display:none;" value="<?php if($nbResultaMoisPrestaM1>0){echo $LigneMoisPrestationM1['InterneCurrent']+$LigneMoisPrestationM1['SubContractorCurrent'];}?>">
											</td>
											<td rowspan="2" style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="M1" id="M1" size="3" style="text-align:center;" value="<?php if($nbResultaMoisPresta>0){echo $LigneMoisPrestation['M1'];}?>">
												<input onKeyUp="nombre(this)" type="texte" name="M1M1" id="M1M1" size="3" style="text-align:center;display:none;" value="<?php if($nbResultaMoisPrestaM1>0){echo $LigneMoisPrestationM1['M1'];}?>">
											</td>
											<td rowspan="2" style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="M2" id="M2" size="3" style="text-align:center;" value="<?php if($nbResultaMoisPresta>0){echo $LigneMoisPrestation['M2'];}?>">
												<input onKeyUp="nombre(this)" type="texte" name="M2M1" id="M2M1" size="3" style="text-align:center;display:none;" value="<?php if($nbResultaMoisPrestaM1>0){echo $LigneMoisPrestationM1['M2'];}?>">
											</td>
											<td rowspan="2" style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="M3" id="M3" size="3" style="text-align:center;" value="<?php if($nbResultaMoisPresta>0){echo $LigneMoisPrestation['M3'];}?>">
												<input onKeyUp="nombre(this)" type="texte" name="M3M1" id="M3M1" size="3" style="text-align:center;display:none;" value="<?php if($nbResultaMoisPrestaM1>0){echo $LigneMoisPrestationM1['M3'];}?>">
											</td>
											<td rowspan="2" style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="M4" id="M4" size="3" style="text-align:center;" value="<?php if($nbResultaMoisPresta>0){echo $LigneMoisPrestation['M4'];}?>">
												<input onKeyUp="nombre(this)" type="texte" name="M4M1" id="M4M1" size="3" style="text-align:center;display:none;" value="<?php if($nbResultaMoisPrestaM1>0){echo $LigneMoisPrestationM1['M4'];}?>">
											</td>
											<td rowspan="2" style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="M5" id="M5" size="3" style="text-align:center;" value="<?php if($nbResultaMoisPresta>0){echo $LigneMoisPrestation['M5'];}?>">
												<input onKeyUp="nombre(this)" type="texte" name="M5M1" id="M5M1" size="3" style="text-align:center;display:none;" value="<?php if($nbResultaMoisPrestaM1>0){echo $LigneMoisPrestationM1['M5'];}?>">
											</td>
											<td rowspan="2" style="border:1px solid black;border-right:2px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="M6" id="M6" size="3" style="text-align:center;" value="<?php if($nbResultaMoisPresta>0){echo $LigneMoisPrestation['M6'];}?>">
												<input onKeyUp="nombre(this)" type="texte" name="M6M1" id="M6M1" size="3" style="text-align:center;display:none;" value="<?php if($nbResultaMoisPrestaM1>0){echo $LigneMoisPrestationM1['M6'];}?>">
											</td>
										</tr>
										<tr>
											<td class="Libelle" style="border:1px solid black;border-bottom:2px solid black;border-left:2px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Sub-contractor";}else{echo "Sous-traitance";} ?></td>
											<td style="border:1px solid black;border-bottom:2px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="subContractorCurrent" id="subContractorCurrent" size="3" style="text-align:center;" value="<?php if($nbResultaMoisPresta>0){echo $LigneMoisPrestation['SubContractorCurrent'];}?>">
												<input onKeyUp="nombre(this)" type="texte" name="subContractorCurrentM1" id="subContractorCurrentM1" size="3" style="text-align:center;display:none;" value="<?php if($nbResultaMoisPrestaM1>0){echo $LigneMoisPrestationM1['SubContractorCurrent'];}?>">
											</td>
										</tr>
										<tr>
											<td></td>
											<td colspan="5"><div id='valeurM1'></div></td>
										</tr>
									</table>
									<br>
									<table width="90%" cellpadding="0" cellspacing="0" align="left">
										<tr>
											<td class="Libelle" align="left"></td>
										</tr>
										<tr id='attentionCharge1' style="display:none;">
											<td class="Libelle" align="left">
												<img  style="cursor:pointer;" width="30px" src='../../Images/attention.png' border='0' /> 
												<?php 
													if($_SESSION['Langue']=="EN"){echo "Data from the Current to M+5 columns are reported from the previous month. M+6=M+5<br> Please check / check these data.";}
													else{echo "Les données des colonnes Actuel à M+5 sont reportées du mois précédents. M+6=M+5<br>Veuillez vérifier/contrôler ces données.";} 
												?>
												<br><br>
											</td>
										</tr>
										<tr id='attentionCharge2' style="display:none;">
											<td class="Libelle" align="left">
												<img  style="cursor:pointer;" width="30px" src='../../Images/attention.png' border='0' /> 
												<?php 
													if($_SESSION['Langue']=="EN"){echo "Data from the M+1 to M+5 columns are reported from the previous month. M+6=M+5<br> Please check / check these data and complete the Current.";}
													else{echo "Les données des colonnes M+1 à M+5 sont reportées du mois précédents. M+6=M+5<br>Veuillez vérifier/contrôler ces données et compléter les colonnes Actuel.";} 
												?>
												<br><br>
											</td>
										</tr>
									</table>
									<br>
									<table width="90%" cellpadding="0" cellspacing="0" align="left">
										<tr>
											<td class="Libelle" align="left"><?php if($_SESSION['Langue']=="EN"){echo "Comment";}else{echo "Commentaire";} ?></td>
										</tr>
										<tr>
											<td class="Libelle" align="left">
												<textarea name="besoinEffectif" id="besoinEffectif" cols="50" rows="5" noresize="noresize"><?php if($nbResultaMoisPresta>0){echo stripslashes($LigneMoisPrestation['BesoinEffectif']);}?></textarea>
												<textarea style="display:none;" name="besoinEffectifM1" id="besoinEffectifM1" cols="50" rows="5" noresize="noresize"><?php if($nbResultaMoisPrestaM1>0){echo stripslashes($LigneMoisPrestationM1['BesoinEffectif']);}?></textarea>
												<br>
												<input onclick="Reporter('CommentaireCharge')" class="Bouton" type="button" value="<?php if($_SESSION['Langue']=="EN"){echo "Retrieve M-1 comment";}else{echo "Récupérer le commentaire M-1";} ?>" />
											</td>
										</tr>
									</table>
								</td>
							</tr>
			
							<tr><td height="4"></td></tr>
						</table>
					</td>
					<td width="25%" valign="top" height="530px;">
						<?php 
							$req="SELECT FR,EN
							FROM moris_aideparagraphe
							WHERE moris_aideparagraphe.NomParagraphe='PRODUCTIVITE' ";
							$resultAide=mysqli_query($bdd,$req);
							$LigneAide=mysqli_fetch_array($resultAide);
						?>
						<table <?php if($Ligne['ProductiviteADesactive']==1){echo "style='display:none'";} ?> class="TableCompetences" width="99%" height="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td class="Libelle" height="5%" style="font-size:15px;"><?php if($_SESSION['Langue']=="EN"){echo "PRODUCTIVITY";}else{echo "PRODUCTIVITE";} ?></td>
								<td style="cursor:pointer;" align="right"><div onclick="AfficheAide('Productivite')"><img id="Productivite" width="30px" src='../../Images/Aide2.png' border='0' /></div></td>
							</tr>
							<tr class="Productivite" style="display:none;"><td height="4"></td></tr>
							<tr class="Productivite" style="display:none;"><td bgcolor="#d5f2ff" style="border-top:2px dotted #0b6acb;" colspan="2" height="4"></td></tr>
							<tr class="Productivite" style="display:none;">
								<td colspan="2" bgcolor="#d5f2ff" style="color:#000000;">
								<?php if($_SESSION['Langue']=="EN"){echo nl2br(str_replace("\\","",stripslashes($LigneAide['EN'])));}else{echo nl2br(str_replace("\\","",stripslashes($LigneAide['FR'])));} ?>
								</td>
							</tr>
							<tr><td style="border-bottom:2px solid #0b6acb;" colspan="2" height="4"></td></tr>
							<tr>
								<td valign="top" colspan="2">
									<table width="90%" cellpadding="0" cellspacing="0" align="center">
										<tr>
											<td class="Libelle" style="border:1px solid black;border-left:2px solid black;border-top:2px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Time sold (h)";}else{echo "Temps vendu (h)";} ?></td>
											<td class="Libelle" style="border:1px solid black;border-top:2px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Objective time (h)";}else{echo "Temps objectif (h)";} ?></td>
											<td class="Libelle" style="border:1px solid black;border-top:2px solid black;border-right:2px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Time Spent (h)";}else{echo "Temps passé (h)";} ?></td>
										</tr>
										<tr>
											<td style="border:1px solid black;border-bottom:2px solid black;border-left:2px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="tempsAlloue" id="tempsAlloue" size="5" oninput="calculeProductivite()" style="text-align:center;" value="<?php if($nbResultaMoisPresta>0){echo $LigneMoisPrestation['TempsAlloue'];}?>">
												<input onKeyUp="nombre(this)" type="texte" name="tempsAlloueM1" id="tempsAlloueM1" size="5" style="text-align:center;display:none;" value="<?php if($nbResultaMoisPrestaM1>0){echo $LigneMoisPrestationM1['TempsAlloue'];}?>">
											</td>
											<td style="border:1px solid black;border-bottom:2px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="tempsObjectif" id="tempsObjectif" size="5" style="text-align:center;" oninput="calculeProductivite()" value="<?php if($nbResultaMoisPresta>0){echo $LigneMoisPrestation['TempsObjectif'];}?>">
												<input onKeyUp="nombre(this)" type="texte" name="tempsObjectifM1" id="tempsObjectifM1" size="5" style="text-align:center;display:none;" value="<?php if($nbResultaMoisPrestaM1>0){echo $LigneMoisPrestationM1['TempsObjectif'];}?>">
											</td>
											<td style="border:1px solid black;border-bottom:2px solid black;border-right:2px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="tempsPasse" id="tempsPasse" size="5" oninput="calculeProductivite()" style="text-align:center;" value="<?php if($nbResultaMoisPresta>0){echo $LigneMoisPrestation['TempsPasse'];}?>">
												<input onKeyUp="nombre(this)" type="texte" name="tempsPasseM1" id="tempsPasseM1" size="5" style="text-align:center;display:none;" value="<?php if($nbResultaMoisPrestaM1>0){echo $LigneMoisPrestationM1['TempsPasse'];}?>">
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td valign="top" colspan="2">
									<table width="90%" cellpadding="0" cellspacing="0" align="center">
										<tr>
											<td class="Libelle" style="border:2px solid black;border-right:1px solid black;border-bottom:1px solid black;" align="center" width="80%"><?php if($_SESSION['Langue']=="EN"){echo "Gross Productivity <br> = Objective time / Time Spent";}else{echo "Productivité Brute<br>=Temps Objectif / Temps Passé";} ?></td>
											<td style="border:2px solid black;border-bottom:1px solid black;" align="center"><div id="prodBrut" width="20%"><?php if($nbResultaMoisPresta>0){if($LigneMoisPrestation['TempsPasse']>0){echo round($LigneMoisPrestation['TempsObjectif']/$LigneMoisPrestation['TempsPasse'],2);}else{echo 0;}} ?></div></td>
										</tr>
										<tr>
											<td class="Libelle" style="border:2px solid black;border-right:1px solid black;border-top:1px solid black;" align="center" width="80%"><?php if($_SESSION['Langue']=="EN"){echo "Corrected Productivity <br> = Time Sold / Time Spent";}else{echo "Productivité Corrigée<br>=Temps Vendu / Temps Passé";} ?></td>
											<td style="border:2px solid black;border-top:1px solid black;" align="center"><div id="prodCorrigee" width="20%"><?php if($nbResultaMoisPresta>0){if($LigneMoisPrestation['TempsPasse']>0){echo round($LigneMoisPrestation['TempsAlloue']/$LigneMoisPrestation['TempsPasse'],2);}else{echo 0;}} ?></div></td>
										</tr>
									</table>
									<br>
									<table width="90%" cellpadding="0" cellspacing="0" align="left">
										<tr>
											<td class="Libelle" align="left"><?php if($_SESSION['Langue']=="EN"){echo "Comment";}else{echo "Commentaire";} ?></td>
										</tr>
										<tr>
											<td class="Libelle" align="left"><textarea name="commentaireProductivite" id="commentaireProductivite" cols="50" rows="5" noresize="noresize"><?php if($nbResultaMoisPresta>0){echo stripslashes($LigneMoisPrestation['CommentaireProductivite']);}?></textarea>
											<textarea style="display:none;" name="commentaireProductiviteM1" id="commentaireProductiviteM1" cols="50" rows="5" noresize="noresize"><?php if($nbResultaMoisPrestaM1>0){echo stripslashes($LigneMoisPrestationM1['CommentaireProductivite']);}?></textarea>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td align="center">
			<table <?php if($Ligne['OTDOQDADesactive']==1 && $Ligne['ManagementADesactive']==1){echo "style='display:none'";} ?> width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="70%" valign="top" height="200px;">
						<?php 
							$req="SELECT FR,EN
							FROM moris_aideparagraphe
							WHERE moris_aideparagraphe.NomParagraphe='QUALITE' ";
							$resultAide=mysqli_query($bdd,$req);
							$LigneAide=mysqli_fetch_array($resultAide);
						?>
						<table <?php if($Ligne['OTDOQDADesactive']==1){echo "style='display:none'";} ?> class="TableCompetences" width="99%" height="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td class="Libelle" height="5%" style="font-size:15px;" colspan="2"><?php if($_SESSION['Langue']=="EN"){echo "On Time Delivery (OTD)";}else{echo "On Time Delivery (OTD)";} ?></td>
								<td style="cursor:pointer;" align="right"><div onclick="AfficheAide('Qualite')"><img id="Qualite" width="30px" src='../../Images/Aide2.png' border='0' /></div></td>
							</tr>
							<tr class="Qualite" style="display:none;"><td height="4"></td></tr>
							<tr class="Qualite" style="display:none;"><td bgcolor="#d5f2ff" style="border-top:2px dotted #0b6acb;" colspan="4" height="4"></td></tr>
							<tr class="Qualite" style="display:none;">
								<td colspan="4" bgcolor="#d5f2ff" style="color:#000000;">
								<?php if($_SESSION['Langue']=="EN"){echo nl2br(str_replace("\\","",stripslashes($LigneAide['EN'])));}else{echo nl2br(str_replace("\\","",stripslashes($LigneAide['FR'])));} ?>
								</td>
							</tr>
							<tr><td style="border-bottom:2px solid #0b6acb;" colspan="4" height="4"></td></tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td width="100%" valign="top" colspan="2">
									<table width="100%" cellpadding="0" cellspacing="0" align="left">
										<?php 
											$nbResultOTDOQD=0;
											if($nbResultaMoisPresta>0){
												$req="SELECT Libelle,ObjectifClient,NbLivrableConforme,NbLivrableTolerance,NbRetourClient,ObjectifTolerance,PasLivrable
													FROM moris_moisprestation_otdoqd
													WHERE Id_MoisPrestation=".$LigneMoisPrestation['Id']." 
													AND bOQD=0 ";
												$resultOTDOQD=mysqli_query($bdd,$req);
												$nbResultOTDOQD=mysqli_num_rows($resultOTDOQD);
											}
											elseif($nbResultaMoisPrestaM1>0){
												$req="SELECT Libelle,ObjectifClient,'' AS NbLivrableConforme,'' AS NbLivrableTolerance,'' AS NbRetourClient,ObjectifTolerance,'' AS PasLivrable
													FROM moris_moisprestation_otdoqd
													WHERE Id_MoisPrestation=".$LigneMoisPrestationM1['Id']." 
													AND bOQD=0 ";
												$resultOTDOQD=mysqli_query($bdd,$req);
												$nbResultOTDOQD=mysqli_num_rows($resultOTDOQD);
											}
										?>
										<tr>
											<td style='width:100%;'>
												<div id='Div_OTD1'>
													<table class="table-striped table-hover table-bordered table-condensed">
														<thead>
															<tr>
																<th width="50px"></th>
																<th width="450px" class="Libelle" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Deliverables";}else{echo "Livrables";} ?></th>
																<th width="85px" class="Libelle" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Customer<br>Objectives<br>%";}else{echo "Objectifs<br>du client<br>%";} ?></th>
																<th width="85px" class="Libelle" style="<?php if($Ligne['ToleranceOTDOQD']==0){echo "display:none;";} ?>" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Tolerance<br>objectives %";}else{echo "Objectif<br>tolérance %";} ?></th>
																<th width="85px" class="Libelle" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Compliant<br>deliverables";}else{echo "Livrables<br>conformes";} ?></th>
																<th width="85px" class="Libelle" style="<?php if($Ligne['ToleranceOTDOQD']==0){echo "display:none;";} ?>" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Deliverable<br>within tolerance";}else{echo "Livrables<br>dans la tolérance";} ?></th>
																<th width="85px" class="Libelle" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Non-compliant<br>deliverables";}else{echo "Livrables<br>non-conformes";} ?></th>
																<th width="85px" class="Libelle" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Realised %";}else{echo "Réalisé %";} ?></th>
																<th width="150px"></th>
																<th width="50px"></th>
															</tr>
														<thead>
													</table>
												</div>
												<div id='Div_OTD3'>
													<table class="table-striped table-hover table-bordered table-condensed">
														<tbody>
															<tr>
																<td width="50px" class="Libelle"></td>
																<td width="450px" class="Libelle" align="center"><?php if($_SESSION['Langue']=="EN"){echo "OTD all deliverable";}else{echo "OTD tous livrables";} ?></td>
																<td width="85px" align="center">
																	<input onKeyUp="nombre(this)" type="texte" name="objectifClientOTD" id="objectifClientOTD" oninput="calculOTD()" style="text-align:center;width:50px;" value="<?php if($nbResultaMoisPresta>0){echo $LigneMoisPrestation['ObjectifClientOTD'];}elseif($nbResultaMoisPrestaM1>0){echo stripslashes($LigneMoisPrestationM1['ObjectifClientOTD']);}?>">
																</td>
																<td width="85px" style="<?php if($Ligne['ToleranceOTDOQD']==0){echo "display:none;";} ?>" align="center"><input onKeyUp="nombre(this)" type="texte" name="objectifToleranceOTD" id="objectifToleranceOTD" oninput="calculOTD()" style="text-align:center;width:50px;" value="<?php if($nbResultaMoisPresta>0){echo $LigneMoisPrestation['ObjectifToleranceOTD'];}elseif($nbResultaMoisPrestaM1>0){echo stripslashes($LigneMoisPrestationM1['ObjectifToleranceOTD']);}?>"></td>
																<td width="85px" align="center"><input onKeyUp="nombre(this)" type="texte" name="nbLivrableConformeOTD" id="nbLivrableConformeOTD" oninput="calculOTD()" style="text-align:center;width:50px;" value="<?php if($nbResultaMoisPresta>0){echo $LigneMoisPrestation['NbLivrableConformeOTD'];}?>"></td>
																<td width="85px" style="<?php if($Ligne['ToleranceOTDOQD']==0){echo "display:none;";} ?>" align="center"><input onKeyUp="nombre(this)" type="texte" name="nbLivrableToleranceOTD" id="nbLivrableToleranceOTD" style="text-align:center;width:50px;" value="<?php if($nbResultaMoisPresta>0){echo $LigneMoisPrestation['NbLivrableToleranceOTD'];}?>"></td>
																<td width="85px" align="center"><input onKeyUp="nombre(this)" type="texte" name="nbRetourClientOTD" id="nbRetourClientOTD" oninput="calculOTD()" style="text-align:center;width:50px;" value="<?php if($nbResultaMoisPresta>0){echo $LigneMoisPrestation['NbRetourClientOTD'];}?>"></td>
																<td width="85px" align="center"><input onKeyUp="nombre(this)" type="texte" name="OTDRealise" id="OTDRealise" disabled="disabled" style="text-align:center;width:50px;" value="<?php if($nbResultaMoisPresta>0){echo round($LigneMoisPrestation['OTD'],2);} ?>"> %</td>
																<td width="150px" class="Libelle">&nbsp;&nbsp;&nbsp;<input type="checkbox" class="PasOTD" id="PasOTD" name="PasOTD" <?php if($nbResultaMoisPresta>0){if($LigneMoisPrestation['PasOTD']==1){echo "checked";}}?> style="text-align:center;"><?php if($_SESSION['Langue']=="EN"){echo "No deliverable";}else{echo "Pas de livrable";} ?></td>
																<td width="50px" class="Libelle"><img style="cursor:pointer;<?php if($nbResultOTDOQD>0){echo "display:none;";}?>" id="BtnPlusOTD" width='20px' src='../../Images/add.png' onclick="AfficherOTD(-1,'blocOTD')" border="0" ></td>
															</tr>
														</tbody>
													</table>
												</div>
											</td>
										</tr>
										<tr><td height="10"></td></tr>
										<tr id="blocOTD" <?php if($nbResultOTDOQD==0){echo "style='display:none'";}?>>
											<td>
												<div id='Div_OTD'>
													<table class="table-striped table-hover table-bordered table-condensed">
														<thead>
															<tr>
																<th width="50px"></th>
																<th width="450px" class="Libelle" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Deliverables";}else{echo "Livrables";} ?></th>
																<th width="85px" class="Libelle" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Customer<br>Objectives<br>%";}else{echo "Objectifs<br>du client<br>%";} ?></th>
																<th width="85px" class="Libelle" style="<?php if($Ligne['ToleranceOTDOQD']==0){echo "display:none;";} ?>" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Tolerance<br>objectives %";}else{echo "Objectif<br>tolérance %";} ?></th>
																<th width="85px" class="Libelle" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Compliant<br>deliverables";}else{echo "Livrables<br>conformes";} ?></th>
																<th width="85px" class="Libelle" style="<?php if($Ligne['ToleranceOTDOQD']==0){echo "display:none;";} ?>" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Deliverable<br>within tolerance";}else{echo "Livrables<br>dans la tolérance";} ?></th>
																<th width="85px" class="Libelle" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Non-compliant<br>deliverables";}else{echo "Livrables<br>non-conformes";} ?></th>
																<th width="85px" class="Libelle" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Realised %";}else{echo "Réalisé %";} ?></th>
																<th width="150px"></th>
																<th width="50px"></th>
															</tr>
														<thead>
													</table>
												</div>
												<div id='Div_OTD2' style='max-height:200px;overflow:auto;'>
													<table class="table-striped table-hover table-bordered table-condensed">
														<tbody>	
														<?php 
															
															for($j=0;$j<100;$j++){
																$Libelle="";
																$nbLivrableConforme="";
																$objectifClient="";
																$nbLivrableTolerance="";
																$nbRetourClient="";
																$objectifTolerance="";
																$PasLivrable=0;
																if($nbResultOTDOQD>0){
																	if($j<$nbResultOTDOQD){
																		$LigneOTDOQD=mysqli_fetch_array($resultOTDOQD);
																		$Libelle=stripslashes($LigneOTDOQD['Libelle']);
																		$nbLivrableConforme=$LigneOTDOQD['NbLivrableConforme'];
																		$objectifClient=$LigneOTDOQD['ObjectifClient'];
																		$nbLivrableTolerance=$LigneOTDOQD['NbLivrableTolerance'];
																		$nbRetourClient=$LigneOTDOQD['NbRetourClient'];
																		$objectifTolerance=$LigneOTDOQD['ObjectifTolerance'];
																		$PasLivrable=$LigneOTDOQD['PasLivrable'];
																	}
																}
														?>
															<tr id="ligne<?php echo $j;?>" <?php if($Libelle==""){echo "style='display:none'";}?> >
																<td width="50px" class="Libelle" align="center"><img style="cursor:pointer;" id="BtnMoinsOTD" width='20px' src='../../Images/moins2.png' onclick="MasquerOTD('<?php echo $j;?>','<?php echo $j-1;?>')" border="0" ></td>
																<td width="450px" class="Libelle" align="center" ><input type="texte" name="LibelleOTD<?php echo $j;?>" id="LibelleOTD<?php echo $j;?>" oninput="calculOTD2()" style="text-align:center;width:100%;" value="<?php echo $Libelle;?>"></td>
																<td width="85px" align="center">
																	<input onKeyUp="nombre(this)" type="texte" name="objectifClientOTD<?php echo $j;?>" id="objectifClientOTD<?php echo $j;?>" oninput="calculOTD2()" style="text-align:center;width:50px;" value="<?php echo $objectifClient;?>">
																</td>
																<td width="85px" style="<?php if($Ligne['ToleranceOTDOQD']==0){echo "display:none;";} ?>" align="center"><input onKeyUp="nombre(this)" type="texte" name="objectifToleranceOTD<?php echo $j;?>" oninput="calculOTD2()" id="objectifToleranceOTD<?php echo $j;?>" style="width:50px;"  style="text-align:center;" value="<?php echo $objectifTolerance;?>"></td>
																<td width="85px" align="center"><input onKeyUp="nombre(this)" type="texte" name="nbLivrableConformeOTD<?php echo $j;?>" id="nbLivrableConformeOTD<?php echo $j;?>" oninput="calculOTD2()" style="text-align:center;width:50px;" value="<?php echo $nbLivrableConforme;?>"></td>
																<td width="85px" style="<?php if($Ligne['ToleranceOTDOQD']==0){echo "display:none;";} ?>" align="center"><input onKeyUp="nombre(this)" type="texte" name="nbLivrableToleranceOTD<?php echo $j;?>" id="nbLivrableToleranceOTD<?php echo $j;?>"  style="text-align:center;width:50px;" value="<?php echo $nbLivrableTolerance;?>"></td>
																<td width="85px" align="center"><input onKeyUp="nombre(this)" type="texte" name="nbRetourClientOTD<?php echo $j;?>" id="nbRetourClientOTD<?php echo $j;?>" oninput="calculOTD2()" style="text-align:center;width:50px;" value="<?php echo $nbRetourClient;?>"></td>
																<td width="85px" align="center"><input onKeyUp="nombre(this)" type="texte" name="OTDRealise<?php echo $j;?>" id="OTDRealise<?php echo $j;?>" disabled="disabled" style="text-align:center;width:50px;" value=""> %</td>
																<td width="150px" class="Libelle">&nbsp;&nbsp;&nbsp;<input type="checkbox" class="PasOTD" id="PasOTD<?php echo $j;?>" name="PasOTD<?php echo $j;?>" <?php if($PasLivrable==1){echo "checked";}?> onClick="calculOTD2()" style="text-align:center;"><?php if($_SESSION['Langue']=="EN"){echo "No deliverable";}else{echo "Pas de livrable";} ?></td>
																<td width="50px" class="Libelle"><img style="cursor:pointer;<?php if($Libelle=="" || $j<($nbResultOTDOQD-1)){echo "display:none";}?>" id="BtnPlusOTD<?php echo $j;?>" width='20px' src='../../Images/add.png' onclick="AfficherOTD('<?php echo $j;?>','<?php echo $j+1;?>')" border="0" ></td>
															</tr>
														<?php 
															}
														?>
														</tbody>
													</table>
												</div>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td width="60%" valign="top">
									<table width="90%" cellpadding="0" cellspacing="0" align="left">
										<tr>
											<td class="Libelle" align="left"><?php if($_SESSION['Langue']=="EN"){echo "OTD calculation method";}else{echo "Mode de calcul de l'OTD";} ?></td>
										</tr>
										<tr>
											<td class="Libelle" align="left">
												<textarea name="modeCalculOTD" id="modeCalculOTD" cols="70" rows="3" noresize="noresize"><?php if($nbResultaMoisPresta>0){echo stripslashes($LigneMoisPrestation['ModeCalculOTD']);}elseif($nbResultaMoisPrestaM1>0){echo stripslashes($LigneMoisPrestationM1['ModeCalculOTD']);} ?></textarea>
											</td>
										</tr>
									</table>
								</td>
								<td width="40%" valign="top">
									<table width="80%" cellpadding="0" cellspacing="0" align="center" style="border:1px solid black;">
										<tr>
											<td class="Libelle" style="border:1px solid black;" align="center" colspan="2">OTD</td>
										</tr>
										<tr>
											<td class="Libelle" style="border:1px solid black;" align="center" width="30%"><?php if($_SESSION['Langue']=="EN"){echo "Main root causes identified";}else{echo "Principales causes identifiées";} ?></td>
											<td class="Libelle" style="border:1px solid black;" align="center" width="70%"><textarea name="causeOTD" id="causeOTD" cols="35" rows="3" noresize="noresize"><?php if($nbResultaMoisPresta>0){echo stripslashes($LigneMoisPrestation['CauseOTD']);}?></textarea></td>
										</tr>
										<tr>
											<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Actions";}else{echo "Actions";} ?></td>
											<td class="Libelle" style="border:1px solid black;" align="center"><textarea name="actionOTD" id="actionOTD" cols="35" rows="3" noresize="noresize"><?php if($nbResultaMoisPresta>0){echo stripslashes($LigneMoisPrestation['ActionOTD']);}?></textarea></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
					<td width="30%" valign="top" height="200px;">
						<?php 
							$req="SELECT FR,EN
							FROM moris_aideparagraphe
							WHERE moris_aideparagraphe.NomParagraphe='MANAGEMENT' ";
							$resultAide=mysqli_query($bdd,$req);
							$LigneAide=mysqli_fetch_array($resultAide);
						?>
						<table <?php if($Ligne['ManagementADesactive']==1){echo "style='display:none'";} ?> class="TableCompetences" width="99%" height="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td class="Libelle" height="5%" colspan="2" style="font-size:15px;"><?php if($_SESSION['Langue']=="EN"){echo "MANAGEMENT";}else{echo "MANAGEMENT";} ?></td>
								<td style="cursor:pointer;" align="right"><div onclick="AfficheAide('Management')"><img id="Management" width="30px" src='../../Images/Aide2.png' border='0' /></div></td>
							</tr>
							<tr class="Management" style="display:none;"><td height="4"></td></tr>
							<tr class="Management" style="display:none;"><td bgcolor="#d5f2ff" style="border-top:2px dotted #0b6acb;" colspan="4" height="4"></td></tr>
							<tr class="Management" style="display:none;">
								<td colspan="4" bgcolor="#d5f2ff" style="color:#000000;">
								<?php if($_SESSION['Langue']=="EN"){echo nl2br(str_replace("\\","",stripslashes($LigneAide['EN'])));}else{echo nl2br(str_replace("\\","",stripslashes($LigneAide['FR'])));} ?>
								</td>
							</tr>
							<tr><td style="border-bottom:2px solid #0b6acb;" colspan="4" height="4"></td></tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td class="Libelle" width="30%"><?php if($_SESSION['Langue']=="EN"){echo "Tendance : ";}else{echo "Tendance : ";} ?></td>
								<td>
									<table cellpadding="0" cellspacing="0">
										<tr>
											<td align="center" ><img width="50px" src='../../Images/VisageContent.png' border='0' /></td>
											<td align="center" ><img width="50px" src='../../Images/VisageMoyen.png' border='0' /></td>
											<td align="center" ><img width="50px" src='../../Images/VisagePasContent.png' border='0' /></td>
										</tr>
										<tr>
											<td align="center" ><input type="radio" class="tendanceManagement" name="tendanceManagement" value="0" <?php if($nbResultaMoisPresta>0){if($LigneMoisPrestation['TendanceManagement']==0){echo "checked";}}?> /></td>
											<td align="center" ><input type="radio" class="tendanceManagement" name="tendanceManagement" value="1" <?php if($nbResultaMoisPresta>0){if($LigneMoisPrestation['TendanceManagement']==1){echo "checked";}}?>/></td>
											<td align="center" ><input type="radio" class="tendanceManagement" name="tendanceManagement" value="2" <?php if($nbResultaMoisPresta>0){if($LigneMoisPrestation['TendanceManagement']==2){echo "checked";}}?>/></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td class="Libelle" width="30%" colspan="2"><?php if($_SESSION['Langue']=="EN"){echo "Events / Irritants : ";}else{echo "Evènements/Irritants : ";} ?></td>
							</tr>
							<tr>
								<td width="30%" colspan="4"><textarea name="evenementManagement" id="evenementManagement" cols="80" rows="5" noresize="noresize"><?php if($nbResultaMoisPresta>0){echo stripslashes($LigneMoisPrestation['EvenementManagement']);}?></textarea></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td align="center">
			<table  <?php if($Ligne['OTDOQDADesactive']==1 && $Ligne['CompetenceADesactive']==1){echo "style='display:none'";} ?> width="100%" cellpadding="0" cellspacing="0">			
				<tr>
					<td width="70%" valign="top" height="300px;">
						<table <?php if($Ligne['OTDOQDADesactive']==1){echo "style='display:none'";} ?> class="TableCompetences" width="99%" height="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td class="Libelle" height="5%" colspan="2" style="border-bottom:2px solid #0b6acb;font-size:15px;"><?php if($_SESSION['Langue']=="EN"){echo "On Quality Delivery (OQD)";}else{echo "On Quality Delivery (OQD)";} ?>&nbsp;</td>
							</tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td width="100%" valign="top" colspan="2">
									<table cellpadding="0" cellspacing="0" align="left">
										<?php 
											$nbResultOTDOQD=0;
											if($nbResultaMoisPresta>0){
												$req="SELECT Libelle,ObjectifClient,NbLivrableConforme,NbLivrableTolerance,NbRetourClient,ObjectifTolerance,PasLivrable
													FROM moris_moisprestation_otdoqd
													WHERE Id_MoisPrestation=".$LigneMoisPrestation['Id']." 
													AND bOQD=1 ";
												$resultOTDOQD=mysqli_query($bdd,$req);
												$nbResultOTDOQD=mysqli_num_rows($resultOTDOQD);
											}
											elseif($nbResultaMoisPrestaM1>0){
												$req="SELECT Libelle,ObjectifClient,'' AS NbLivrableConforme,'' AS NbLivrableTolerance,'' AS NbRetourClient,ObjectifTolerance,'' AS PasLivrable
													FROM moris_moisprestation_otdoqd
													WHERE Id_MoisPrestation=".$LigneMoisPrestationM1['Id']." 
													AND bOQD=1 ";
												$resultOTDOQD=mysqli_query($bdd,$req);
												$nbResultOTDOQD=mysqli_num_rows($resultOTDOQD);
											}
										?>
										<tr>
											<td width="100%">
												<div id='Div_OQD1'>
													<table class="table-striped table-hover table-bordered table-condensed">
														<thead>
															<tr>
																<th width="50px"></th>
																<th width="450px" class="Libelle" align="center"></th>
																<th width="85px" class="Libelle" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Customer Objectives<br>%";}else{echo "Objectifs du client<br>%";} ?></th>
																<th width="85px" class="Libelle" style="<?php if($Ligne['ToleranceOTDOQD']==0){echo "display:none;";} ?>" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Tolerance<br>objectives %";}else{echo "Objectif<br>tolérance %";} ?></th>
																<th width="85px" class="Libelle" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Compliant<br>deliverables";}else{echo "Livrables<br>conformes";} ?></th>
																<th width="85px" class="Libelle" style="<?php if($Ligne['ToleranceOTDOQD']==0){echo "display:none;";} ?>" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Deliverable<br>within tolerance";}else{echo "Livrables<br>dans la tolérance";} ?></th>
																<th width="85px" class="Libelle" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Non-compliant<br>deliverables";}else{echo "Livrables<br>non-conformes";} ?></th>
																<th width="85px" class="Libelle" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Realised %";}else{echo "Réalisé %";} ?></th>
																<th width="150px"></th>
																<th width="50px"></th>
															</tr>
														</thead>
													</table>
												</div>
												<div id='Div_OQD3'>
													<table class="table-striped table-hover table-bordered table-condensed">
														<tbody>
															<tr>
																<td width="50px"></td>
																<td width="450px" class="Libelle" align="center"><?php if($_SESSION['Langue']=="EN"){echo "OQD all deliverable";}else{echo "OQD tous livrables";} ?></td>
																<td width="85px" align="center">
																	<input onKeyUp="nombre(this)" type="texte" name="objectifClientOQD" id="objectifClientOQD" size="6" oninput="calculOQD()" style="text-align:center;width:width:100%;" value="<?php if($nbResultaMoisPresta>0){echo $LigneMoisPrestation['ObjectifClientOQD'];}elseif($nbResultaMoisPrestaM1>0){echo stripslashes($LigneMoisPrestationM1['ObjectifClientOQD']);}?>">
																</td>
																<td width="85px" style="<?php if($Ligne['ToleranceOTDOQD']==0){echo "display:none;";} ?>" align="center"><input onKeyUp="nombre(this)" type="texte" name="objectifToleranceOQD" id="objectifToleranceOQD" style="text-align:center;width:50px;" value="<?php if($nbResultaMoisPresta>0){echo $LigneMoisPrestation['ObjectifToleranceOQD'];}elseif($nbResultaMoisPrestaM1>0){echo stripslashes($LigneMoisPrestationM1['ObjectifToleranceOQD']);}?>"></td>
																<td width="85px" align="center"><input onKeyUp="nombre(this)" type="texte" name="nbLivrableConformeOQD" id="nbLivrableConformeOQD" oninput="calculOQD()" style="text-align:center;width:50px;" value="<?php if($nbResultaMoisPresta>0){echo $LigneMoisPrestation['NbLivrableConformeOQD'];}?>"></td>
																<td width="85px" style="<?php if($Ligne['ToleranceOTDOQD']==0){echo "display:none;";} ?>" align="center"><input onKeyUp="nombre(this)" type="texte" name="nbLivrableToleranceOQD" id="nbLivrableToleranceOQD" oninput="calculOQD()" style="text-align:center;width:50px;" value="<?php if($nbResultaMoisPresta>0){echo $LigneMoisPrestation['NbLivrableToleranceOQD'];}?>"></td>
																<td width="85px" align="center"><input onKeyUp="nombre(this)" type="texte" name="nbRetourClientOQD" id="nbRetourClientOQD" oninput="calculOQD()" style="text-align:center;width:50px;" value="<?php if($nbResultaMoisPresta>0){echo $LigneMoisPrestation['NbRetourClientOQD'];}?>"></td>
																<td width="85px" align="center"><input onKeyUp="nombre(this)" type="texte" name="OQDRealise" id="OQDRealise" disabled="disabled" style="text-align:center;width:50px;" value="<?php if($nbResultaMoisPresta>0){echo round($LigneMoisPrestation['OQD'],2);} ?>"> %</td>
																<td width="150px" class="Libelle">&nbsp;&nbsp;&nbsp;<input type="checkbox" class="PasOQD" id="PasOQD" name="PasOQD" <?php if($nbResultaMoisPresta>0){if($LigneMoisPrestation['PasOQD']==1){echo "checked";}}?> style="text-align:center;"><?php if($_SESSION['Langue']=="EN"){echo "No deliverable";}else{echo "Pas de livrable";} ?></td>
																<td width="50px" class="Libelle"><img style="cursor:pointer;<?php if($nbResultOTDOQD>0){echo "display:none;";}?>" id="BtnPlusOQD" width='20px' src='../../Images/add.png' onclick="AfficherOQD(-1,'blocOQD')" border="0" ></td>
															</tr>
														</tbody>
													</table>
												</div>
											</td>
										</tr>
										<tr><td height="10"></td></tr>
										<tr id="blocOQD" <?php if($nbResultOTDOQD==0){echo "style='display:none'";}?>>
											<td>
												<div id='Div_OQD'>
													<table class="table-striped table-hover table-bordered table-condensed">
														<thead>
															<tr>
																<th width="50px"></th>
																<th width="450px" class="Libelle" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Deliverables";}else{echo "Livrables";} ?></th>
																<th width="85px" class="Libelle" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Customer Objectives<br>%";}else{echo "Objectifs du client<br>%";} ?></th>
																<th width="85px" class="Libelle" style="<?php if($Ligne['ToleranceOTDOQD']==0){echo "display:none;";} ?>" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Tolerance<br>objectives %";}else{echo "Objectif<br>tolérance %";} ?></th>
																<th width="85px" class="Libelle" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Compliant<br>deliverables";}else{echo "Livrables<br>conformes";} ?></th>
																<th width="85px" class="Libelle" style="<?php if($Ligne['ToleranceOTDOQD']==0){echo "display:none;";} ?>" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Deliverable<br>within tolerance";}else{echo "Livrables<br>dans la tolérance";} ?></th>
																<th width="85px" class="Libelle" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Non-compliant<br>deliverables";}else{echo "Livrables<br>non-conformes";} ?></th>
																<th width="85px" class="Libelle" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Realised %";}else{echo "Réalisé %";} ?></th>
																<th width="150px"></th>
																<th width="50px"></th>
															</tr>
														<thead>
													</table>
												</div>
												<div id='Div_OQD2' style='max-height:200px;overflow:auto;'>
													<table class="table-striped table-hover table-bordered table-condensed">
														<tbody>	
														<?php 
															
															for($j=0;$j<100;$j++){
																$Libelle="";
																$nbLivrableConforme="";
																$objectifClient="";
																$nbLivrableTolerance="";
																$nbRetourClient="";
																$objectifTolerance="";
																$PasLivrable=0;
																if($nbResultOTDOQD>0){
																	if($j<$nbResultOTDOQD){
																		$LigneOTDOQD=mysqli_fetch_array($resultOTDOQD);
																		$Libelle=stripslashes($LigneOTDOQD['Libelle']);
																		$nbLivrableConforme=$LigneOTDOQD['NbLivrableConforme'];
																		$objectifClient=$LigneOTDOQD['ObjectifClient'];
																		$nbLivrableTolerance=$LigneOTDOQD['NbLivrableTolerance'];
																		$nbRetourClient=$LigneOTDOQD['NbRetourClient'];
																		$objectifTolerance=$LigneOTDOQD['ObjectifTolerance'];
																		$PasLivrable=$LigneOTDOQD['PasLivrable'];
																	}
																}
														?>
															<tr id="ligneOQD<?php echo $j;?>" <?php if($Libelle==""){echo "style='display:none'";}?> >
																<td width="50px" class="Libelle" align="center"><img style="cursor:pointer;" id="BtnMoinsOQD" width='20px' src='../../Images/moins2.png' onclick="MasquerOQD('<?php echo $j;?>','<?php echo $j-1;?>')" border="0" ></td>
																<td width="450px" class="Libelle" align="center" ><input type="texte" name="LibelleOQD<?php echo $j;?>" id="LibelleOQD<?php echo $j;?>" oninput="calculOQD2()" style="text-align:center;width:100%;" value="<?php echo $Libelle;?>"></td>
																<td width="85px" align="center">
																	<input onKeyUp="nombre(this)" type="texte" name="objectifClientOQD<?php echo $j;?>" id="objectifClientOQD<?php echo $j;?>" oninput="calculOQD2()" style="text-align:center;width:50px;" value="<?php echo $objectifClient;?>">
																</td>
																<td width="85px" style="<?php if($Ligne['ToleranceOTDOQD']==0){echo "display:none;";} ?>" align="center"><input onKeyUp="nombre(this)" type="texte" name="objectifToleranceOQD<?php echo $j;?>" oninput="calculOQD2()" id="objectifToleranceOQD<?php echo $j;?>" style="width:50px;"  style="text-align:center;" value="<?php echo $objectifTolerance;?>"></td>
																<td width="85px" align="center"><input onKeyUp="nombre(this)" type="texte" name="nbLivrableConformeOQD<?php echo $j;?>" id="nbLivrableConformeOQD<?php echo $j;?>" oninput="calculOQD2()" style="text-align:center;width:50px;" value="<?php echo $nbLivrableConforme;?>"></td>
																<td width="85px" style="<?php if($Ligne['ToleranceOTDOQD']==0){echo "display:none;";} ?>" align="center"><input onKeyUp="nombre(this)" type="texte" name="nbLivrableToleranceOQD<?php echo $j;?>" id="nbLivrableToleranceOQD<?php echo $j;?>"  style="text-align:center;width:50px;" value="<?php echo $nbLivrableTolerance;?>"></td>
																<td width="85px" align="center"><input onKeyUp="nombre(this)" type="texte" name="nbRetourClientOQD<?php echo $j;?>" id="nbRetourClientOQD<?php echo $j;?>" oninput="calculOQD2()" style="text-align:center;width:50px;" value="<?php echo $nbRetourClient;?>"></td>
																<td width="85px" align="center"><input onKeyUp="nombre(this)" type="texte" name="OQDRealise<?php echo $j;?>" id="OQDRealise<?php echo $j;?>" disabled="disabled" style="text-align:center;width:50px;" value=""> %</td>
																<td width="150px" class="Libelle">&nbsp;&nbsp;&nbsp;<input type="checkbox" class="PasOQD" id="PasOQD<?php echo $j;?>" name="PasOTD<?php echo $j;?>" <?php if($PasLivrable==1){echo "checked";}?> onClick="calculOQD2()" style="text-align:center;"><?php if($_SESSION['Langue']=="EN"){echo "No deliverable";}else{echo "Pas de livrable";} ?></td>
																<td width="50px" class="Libelle"><img style="cursor:pointer;<?php if($Libelle=="" || $j<($nbResultOTDOQD-1)){echo "display:none";}?>" id="BtnPlusOQD<?php echo $j;?>" width='20px' src='../../Images/add.png' onclick="AfficherOQD('<?php echo $j;?>','<?php echo $j+1;?>')" border="0" ></td>
															</tr>
														<?php 
															}
														?>
														</tbody>
													</table>
												</div>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td width="60%" valign="top">
									<table width="90%" cellpadding="0" cellspacing="0" align="left">
										<tr>
											<td class="Libelle" align="left"><?php if($_SESSION['Langue']=="EN"){echo "OQD calculation method";}else{echo "Mode de calcul de l'OQD";} ?></td>
										</tr>
										<tr>
											<td class="Libelle" align="left">
												<textarea name="modeCalculOQD" id="modeCalculOQD" cols="70" rows="3" noresize="noresize"><?php if($nbResultaMoisPresta>0){echo stripslashes($LigneMoisPrestation['ModeCalculOQD']);}elseif($nbResultaMoisPrestaM1>0){echo stripslashes($LigneMoisPrestationM1['ModeCalculOQD']);} ?></textarea>
												<textarea name="modeCalculOQDM1" id="modeCalculOQDM1" cols="70" rows="3" style="display:none;" noresize="noresize"><?php if($nbResultaMoisPrestaM1>0){echo stripslashes($LigneMoisPrestationM1['ModeCalculOQD']);}?></textarea>
											</td>
										</tr>
									</table>
								</td>
								<td width="40%" valign="top">
									<table width="80%" cellpadding="0" cellspacing="0" align="center" style="border:1px solid black;">
										<tr>
											<td class="Libelle" style="border:1px solid black;" align="center" colspan="2">OQD</td>
										</tr>
										<tr>
											<td class="Libelle" style="border:1px solid black;" align="center" width="30%"><?php if($_SESSION['Langue']=="EN"){echo "Main root causes identified";}else{echo "Principales causes identifiées";} ?></td>
											<td class="Libelle" style="border:1px solid black;" align="center" width="70%"><textarea name="causeOQD" id="causeOQD" cols="35" rows="3" noresize="noresize"><?php if($nbResultaMoisPresta>0){echo stripslashes($LigneMoisPrestation['CauseOQD']);}?></textarea></td>
										</tr>
										<tr>
											<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Actions";}else{echo "Actions";} ?></td>
											<td class="Libelle" style="border:1px solid black;" align="center"><textarea name="actionOQD" id="actionOQD" cols="35" rows="3" noresize="noresize"><?php if($nbResultaMoisPresta>0){echo stripslashes($LigneMoisPrestation['ActionOQD']);}?></textarea></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr><td height="4"></td></tr>
						</table>
					</td>
					<td width="30%" valign="top" height="200px;">
						<?php 
							$req="SELECT FR,EN
							FROM moris_aideparagraphe
							WHERE moris_aideparagraphe.NomParagraphe='COMPETENCES' ";
							$resultAide=mysqli_query($bdd,$req);
							$LigneAide=mysqli_fetch_array($resultAide);
						?>
						<table <?php if($Ligne['CompetenceADesactive']==1){echo "style='display:none'";} ?> class="TableCompetences" height="100%" width="99%" cellpadding="0" cellspacing="0">
							<tr>
								<td class="Libelle" height="5%" style="font-size:15px;"><?php if($_SESSION['Langue']=="EN"){echo "SKILLS";}else{echo "COMPETENCES";} ?></td>
								<td style="cursor:pointer;" align="right"><div onclick="AfficheAide('Competences')"><img id="Competences" width="30px" src='../../Images/Aide2.png' border='0' /></div></td>
							</tr>
							<tr class="Competences" style="display:none;"><td height="4"></td></tr>
							<tr class="Competences" style="display:none;"><td bgcolor="#d5f2ff" style="border-top:2px dotted #0b6acb;" colspan="2" height="4"></td></tr>
							<tr class="Competences" style="display:none;">
								<td colspan="2" bgcolor="#d5f2ff" style="color:#000000;">
								<?php if($_SESSION['Langue']=="EN"){echo nl2br(str_replace("\\","",stripslashes($LigneAide['EN'])));}else{echo nl2br(str_replace("\\","",stripslashes($LigneAide['FR'])));} ?>
								</td>
							</tr>
							<tr><td style="border-bottom:2px solid #0b6acb;" colspan="2" height="4"></td></tr>
							<tr>
								<td colspan="2" align='right'>
									<?php if($_SESSION['Langue']=="EN"){echo "From the Table of Versatility";}else{echo "Issue du Tableau de polyvalence";} ?>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<table width="90%" cellpadding="0" cellspacing="0" align="center">
										<tr>
											<td class="Libelle" style="border:1px solid black;border-left:2px solid black;border-top:2px solid black;" width="50%" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Number of X total";}else{echo "Nombre de X total";} ?></td>
											<td style="border:1px solid black;border-top:2px solid black;" align="center" width="25%">
												<input onKeyUp="nombre(this)" type="texte" oninput="calculCompetences()" name="nbXTableauPolyvalence" id="nbXTableauPolyvalence" size="5" style="text-align:center;" value="<?php if($nbResultaMoisPresta>0){echo $LigneMoisPrestation['NbXTableauPolyvalence'];}?>">
												<input onKeyUp="nombre(this)" type="texte" name="nbXTableauPolyvalenceM1" id="nbXTableauPolyvalenceM1" size="5" style="text-align:center;display:none;" value="<?php if($nbResultaMoisPrestaM1>0){echo $LigneMoisPrestationM1['NbXTableauPolyvalence'];}?>">
											</td>
											<td style="border:1px solid black;border-top:2px solid black;border-bottom:2px solid black;border-right:2px solid black;" width="25%" align="center" valign="center" rowspan="2"><div id="pourcentageCompetences" style="display: inline-block;"><?php if($nbResultaMoisPresta>0){if($LigneMoisPrestation['NbXTableauPolyvalence']>0 || $LigneMoisPrestation['NbLTableauPolyvalence']>0){echo round(($LigneMoisPrestation['NbXTableauPolyvalence']/($LigneMoisPrestation['NbXTableauPolyvalence']+$LigneMoisPrestation['NbLTableauPolyvalence']))*100,2);}else{echo 0;}} ?></div>%</td>
										</tr>
										<tr>
											<td class="Libelle" style="border:1px solid black;border-left:2px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Number of L total";}else{echo "Nombre de L total";} ?></td>
											<td style="border:1px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" oninput="calculCompetences()" name="nbLTableauPolyvalence" id="nbLTableauPolyvalence" size="5" style="text-align:center;" value="<?php if($nbResultaMoisPresta>0){echo $LigneMoisPrestation['NbLTableauPolyvalence'];}?>">
												<input onKeyUp="nombre(this)" type="texte" name="nbLTableauPolyvalenceM1" id="nbLTableauPolyvalenceM1" size="5" style="text-align:center;display:none;" value="<?php if($nbResultaMoisPrestaM1>0){echo $LigneMoisPrestationM1['NbLTableauPolyvalence'];}?>">
											</td>
										</tr>
										<tr>
											<td class="Libelle" style="border:1px solid black;border-bottom:2px solid black;border-left:2px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Number of mono skills";}else{echo "Nombre de mono compétences";} ?></td>
											<td style="border:1px solid black;border-bottom:2px solid black;border-right:2px solid black;" align="center">
												<input onKeyUp="nombre(this)" type="texte" name="nbMonoCompetence" id="nbMonoCompetence" size="5" style="text-align:center;" value="<?php if($nbResultaMoisPresta>0){echo $LigneMoisPrestation['NbMonoCompetence'];}?>">
												<input onKeyUp="nombre(this)" type="texte" name="nbMonoCompetenceM1" id="nbMonoCompetenceM1" size="5" style="text-align:center;display:none;" value="<?php if($nbResultaMoisPrestaM1>0){echo $LigneMoisPrestationM1['NbMonoCompetence'];}?>">
											</td>
										</tr>
									</table>
									<br>
									<?php 
										$tauxQualif=0;
										
										$Requetes_Liste_Qualifs="
										SELECT
											DISTINCT new_competences_qualification.Id,
											new_competences_qualification.Id_Categorie_Qualification,
											new_competences_qualification.Libelle
										FROM
											new_competences_qualification
											LEFT JOIN new_competences_prestation_qualification ON new_competences_qualification.Id=new_competences_prestation_qualification.Id_Qualification
											LEFT JOIN new_competences_categorie_qualification ON new_competences_qualification.Id_Categorie_Qualification=new_competences_categorie_qualification.Id
										WHERE
											new_competences_prestation_qualification.Id_Prestation IN (".$PrestationSelect.")";
										$Result_Liste_Qualification=mysqli_query($bdd,$Requetes_Liste_Qualifs);
										$nbenreg=mysqli_num_rows($Result_Liste_Qualification);
										if($nbenreg>0){
											$Derniere_Categorie=0;
											$nbVQSX = 0;
											$nbVQSXBL = 0;
											
											while($Ligne_Liste_Qualification=mysqli_fetch_array($Result_Liste_Qualification)){
												if($Derniere_Categorie!=$Ligne_Liste_Qualification['Id_Categorie_Qualification']){
													//NB QUALIF CORRECT
													$Requete_Ligne_Qualifications="SELECT count(*) FROM (";
													$Requete_Ligne_Qualifications.="SELECT * FROM (SELECT new_competences_relation.Id, new_competences_relation.Id_Personne, new_competences_relation.Evaluation,(@row_number:=@row_number + 1) AS rnk FROM new_competences_relation";
													$Requete_Ligne_Qualifications.=" LEFT JOIN new_competences_personne_prestation ON new_competences_personne_prestation.Id_Personne ";
													$Requete_Ligne_Qualifications.="= new_competences_relation.Id_Personne ";
													$Requete_Ligne_Qualifications.="WHERE new_competences_personne_prestation.Id_Prestation IN (".$PrestationSelect.") AND ";
													$Requete_Ligne_Qualifications.="new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."' AND ";
													$Requete_Ligne_Qualifications.="new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."'" ;
													$Requete_Ligne_Qualifications.=" AND new_competences_relation.Type='Qualification' AND new_competences_relation.Suppr=0 AND new_competences_relation.Evaluation<>'' ";
													$Requete_Ligne_Qualifications.=" AND (new_competences_relation.Date_Fin>='".date('Y-m-d')."' OR new_competences_relation.Date_Fin<='0001-01-01')";
													$Requete_Ligne_Qualifications.=" AND new_competences_relation.Id_Qualification_Parrainage=".$Ligne_Liste_Qualification['Id']." ORDER BY new_competences_relation.Date_QCM DESC) AS toto ";
													$Requete_Ligne_Qualifications.=" GROUP BY toto.Id_Personne) AS toto2 ";

													$Requete_Ligne_Qualifications.=" WHERE toto2.Evaluation='Q' or toto2.Evaluation='S' OR toto2.Evaluation='V' OR toto2.Evaluation='X' ";
													$Requete_Ligne_Qualifications.=" or toto2.Evaluation='Low' or toto2.Evaluation='Medium' or toto2.Evaluation='High' ";
													$Result_Ligne_Qualifications=mysqli_query($bdd,$Requete_Ligne_Qualifications);
													$Qualif=mysqli_fetch_array($Result_Ligne_Qualifications);
													
													//Somme QUALIF
													$Requete_Ligne_QualificationsSomme="SELECT count(*) FROM (";
													$Requete_Ligne_QualificationsSomme.="SELECT * FROM (SELECT new_competences_relation.Id, new_competences_relation.Id_Personne, new_competences_relation.Evaluation,(@row_number:=@row_number + 1) AS rnk FROM new_competences_relation";
													$Requete_Ligne_QualificationsSomme.=" LEFT JOIN new_competences_personne_prestation ON new_competences_personne_prestation.Id_Personne ";
													$Requete_Ligne_QualificationsSomme.="= new_competences_relation.Id_Personne ";
													$Requete_Ligne_QualificationsSomme.="WHERE new_competences_personne_prestation.Id_Prestation IN (".$PrestationSelect.") AND ";
													$Requete_Ligne_QualificationsSomme.="new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."' AND ";
													$Requete_Ligne_QualificationsSomme.="new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."'" ;
													$Requete_Ligne_QualificationsSomme.=" AND new_competences_relation.Type='Qualification' AND new_competences_relation.Suppr=0 AND new_competences_relation.Evaluation<>'' ";
													$Requete_Ligne_QualificationsSomme.=" AND (new_competences_relation.Date_Fin>='".date('Y-m-d')."' OR new_competences_relation.Date_Fin<='0001-01-01')";
													$Requete_Ligne_QualificationsSomme.=" AND new_competences_relation.Id_Qualification_Parrainage=".$Ligne_Liste_Qualification['Id']." ORDER BY new_competences_relation.Date_QCM DESC) AS toto ";
													$Requete_Ligne_QualificationsSomme.=" GROUP BY toto.Id_Personne) AS toto2 ";

													$Requete_Ligne_QualificationsSomme.=" WHERE toto2.Evaluation='Q' or toto2.Evaluation='S' OR toto2.Evaluation='V' OR toto2.Evaluation='X' ";
													$Requete_Ligne_QualificationsSomme.="or toto2.Evaluation='B' or toto2.Evaluation='Bi' or toto2.Evaluation='L' or toto2.Evaluation='Low' or toto2.Evaluation='Medium' or toto2.Evaluation='High' ";
													
													$Result_Ligne_QualificationsSomme=mysqli_query($bdd,$Requete_Ligne_QualificationsSomme);
													$QualifSomme=mysqli_fetch_array($Result_Ligne_QualificationsSomme);

													//RESULTAT
													$nbVQSX += $Qualif[0]; 
													$nbVQSXBL += $QualifSomme[0];
													$Derniere_Categorie=$Ligne_Liste_Qualification['Id_Categorie_Qualification'];
												}
												else{
													//NB QUALIF CORRECT
													$Requete_Ligne_Qualifications="SELECT count(*) FROM (";
													$Requete_Ligne_Qualifications.="SELECT * FROM (SELECT new_competences_relation.Id, new_competences_relation.Id_Personne, new_competences_relation.Evaluation,(@row_number:=@row_number + 1) AS rnk FROM new_competences_relation";
													$Requete_Ligne_Qualifications.=" LEFT JOIN new_competences_personne_prestation ON new_competences_personne_prestation.Id_Personne ";
													$Requete_Ligne_Qualifications.="= new_competences_relation.Id_Personne ";
													$Requete_Ligne_Qualifications.="WHERE new_competences_personne_prestation.Id_Prestation IN (".$PrestationSelect.") AND ";
													$Requete_Ligne_Qualifications.="new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."' AND ";
													$Requete_Ligne_Qualifications.="new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."'" ;
													$Requete_Ligne_Qualifications.=" AND new_competences_relation.Type='Qualification' AND new_competences_relation.Suppr=0 AND new_competences_relation.Evaluation<>'' ";
													$Requete_Ligne_Qualifications.=" AND (new_competences_relation.Date_Fin>='".date('Y-m-d')."' OR new_competences_relation.Date_Fin<='0001-01-01')";
													$Requete_Ligne_Qualifications.=" AND new_competences_relation.Id_Qualification_Parrainage=".$Ligne_Liste_Qualification['Id']." ORDER BY new_competences_relation.Date_QCM DESC) AS toto";
													$Requete_Ligne_Qualifications.=" GROUP BY toto.Id_Personne) AS toto2 ";

													$Requete_Ligne_Qualifications.=" WHERE toto2.Evaluation='Q' or toto2.Evaluation='S' OR toto2.Evaluation='V' OR toto2.Evaluation='X' ";
													$Requete_Ligne_Qualifications.=" or toto2.Evaluation='Low' or toto2.Evaluation='Medium' or toto2.Evaluation='High' ";
													
													$Result_Ligne_Qualifications=mysqli_query($bdd,$Requete_Ligne_Qualifications);
													$Qualif=mysqli_fetch_array($Result_Ligne_Qualifications);
													
													//Somme QUALIF
													$Requete_Ligne_QualificationsSomme="SELECT count(*) FROM (";
													$Requete_Ligne_QualificationsSomme.="SELECT * FROM (SELECT new_competences_relation.Id, new_competences_relation.Id_Personne, new_competences_relation.Evaluation,(@row_number:=@row_number + 1) AS rnk FROM new_competences_relation";
													$Requete_Ligne_QualificationsSomme.=" LEFT JOIN new_competences_personne_prestation ON new_competences_personne_prestation.Id_Personne ";
													$Requete_Ligne_QualificationsSomme.="= new_competences_relation.Id_Personne ";
													$Requete_Ligne_QualificationsSomme.="WHERE new_competences_personne_prestation.Id_Prestation IN (".$PrestationSelect.") AND ";
													$Requete_Ligne_QualificationsSomme.="new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."' AND ";
													$Requete_Ligne_QualificationsSomme.="new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."'" ;
													$Requete_Ligne_QualificationsSomme.=" AND new_competences_relation.Type='Qualification' AND new_competences_relation.Suppr=0 AND new_competences_relation.Evaluation<>'' ";
													$Requete_Ligne_QualificationsSomme.=" AND (new_competences_relation.Date_Fin>='".date('Y-m-d')."' OR new_competences_relation.Date_Fin<='0001-01-01')";
													$Requete_Ligne_QualificationsSomme.=" AND new_competences_relation.Id_Qualification_Parrainage=".$Ligne_Liste_Qualification['Id']." ORDER BY new_competences_relation.Date_QCM DESC) AS toto ";
													$Requete_Ligne_QualificationsSomme.=" GROUP BY toto.Id_Personne) AS toto2 ";

													$Requete_Ligne_QualificationsSomme.=" WHERE toto2.Evaluation='Q' or toto2.Evaluation='S' OR toto2.Evaluation='V' OR toto2.Evaluation='X' ";
													$Requete_Ligne_QualificationsSomme.="or toto2.Evaluation='B' or toto2.Evaluation='L' or toto2.Evaluation='Low' or toto2.Evaluation='Medium' or toto2.Evaluation='High' ";
													
													$Result_Ligne_QualificationsSomme=mysqli_query($bdd,$Requete_Ligne_QualificationsSomme);
													$QualifSomme=mysqli_fetch_array($Result_Ligne_QualificationsSomme);
													//RESULTAT
													$nbVQSX += $Qualif[0]; 
													$nbVQSXBL += $QualifSomme[0];
												}
												
											}
											$valeur = 0;
											if($nbVQSXBL>0){
												$valeur = round(($nbVQSX / $nbVQSXBL) * 100);
											}
											
											$tauxQualif=$valeur;

										}
									?>
									
									<br>
									<table width="90%" cellpadding="0" cellspacing="0" align="center">
										<tr>
											<td class="Libelle" style="border:1px solid black;border-left:2px solid black;border-top:2px solid black;" width="50%" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Qualifying rate";}else{echo "Taux de qualif";} ?></td>
											<td style="border:1px solid black;border-top:2px solid black;" align="center" width="25%">
												<input onKeyUp="nombre(this)" type="texte" name="tauxQualif" id="tauxQualif" disabled="disabled" size="5" style="text-align:center;" value="<?php if($nbResultaMoisPresta>0){echo $LigneMoisPrestation['TauxQualif'];}?>">
												<input onKeyUp="nombre(this)" type="texte" name="tauxQualif2" id="tauxQualif2" size="5" style="text-align:center;display:none;" value="<?php if($nbResultaMoisPresta>0){echo $LigneMoisPrestation['TauxQualif'];}?>">
												<input onKeyUp="nombre(this)" type="texte" name="tauxQualifRecup" id="tauxQualifRecup" size="5" style="text-align:center;display:none;" value="<?php echo $tauxQualif; ?>"> %
											</td>
											<td>
												<div><input onclick="Reporter('TauxQualif')" class="Bouton" type="button" value="Importer" /></div>
											</td>
										</tr>
									</table>
									<br>
									<table width="90%" cellpadding="0" cellspacing="0" align="left">
										<tr>
											<td class="Libelle" align="left"><?php if($_SESSION['Langue']=="EN"){echo "Comment / Training Action Plan";}else{echo "Commentaire / Plan d'action formation";} ?></td>
										</tr>
										<tr>
											<td class="Libelle" align="left"><textarea name="commentairePlanActionFormation" id="commentairePlanActionFormation" cols="80" rows="3" noresize="noresize"><?php if($nbResultaMoisPresta>0){echo stripslashes($LigneMoisPrestation['CommentairePlanActionFormation']);}?></textarea></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td align="center">
			<table <?php if($Ligne['SecuriteADesactive']==1){echo "style='display:none'";} ?> width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="75%" valign="top" height="150px;">
						<?php 
							$req="SELECT FR,EN
							FROM moris_aideparagraphe
							WHERE moris_aideparagraphe.NomParagraphe='SECURITE' ";
							$resultAide=mysqli_query($bdd,$req);
							$LigneAide=mysqli_fetch_array($resultAide);
						?>
						<table class="TableCompetences" height="100%" width="99%" cellpadding="0" cellspacing="0">
							<tr>
								<td class="Libelle" height="5%" style="font-size:15px;"><?php if($_SESSION['Langue']=="EN"){echo "SECURITY";}else{echo "SECURITE";} ?></td>
								<td style="cursor:pointer;" align="right"><div onclick="AfficheAide('Securite')"><img id="Securite" width="30px" src='../../Images/Aide2.png' border='0' /></div></td>
							</tr>
							<tr class="Securite" style="display:none;"><td height="4"></td></tr>
							<tr class="Securite" style="display:none;"><td bgcolor="#d5f2ff" style="border-top:2px dotted #0b6acb;" colspan="2" height="4"></td></tr>
							<tr class="Securite" style="display:none;">
								<td colspan="2" bgcolor="#d5f2ff" style="color:#000000;">
								<?php if($_SESSION['Langue']=="EN"){echo nl2br(str_replace("\\","",stripslashes($LigneAide['EN'])));}else{echo nl2br(str_replace("\\","",stripslashes($LigneAide['FR'])));} ?>
								</td>
							</tr>
							<tr><td style="border-bottom:2px solid #0b6acb;" colspan="2" height="4"></td></tr>
							<tr><td height="4"></td></tr>
							<tr <?php if($Ligne['SecuriteADesactive']==-1 || $annee."_".$mois<="2023_06"){}else{echo "style='display:none'";} ?>>
								<td class="Libelle">&nbsp;&nbsp;&nbsp;<input type="checkbox" class="PasAT" id="PasAT" name="PasAT" <?php if($nbResultaMoisPresta>0){if($LigneMoisPrestation['PasAT']==1){echo "checked";}}?> style="text-align:center;"><?php if($_SESSION['Langue']=="EN"){echo "No work accident";}else{echo "Pas d'AT";} ?></td>
							</tr>
							<tr><td height="4"></td></tr>
							<tr <?php if($Ligne['SecuriteADesactive']==-1 || $annee."_".$mois<="2023_06"){}else{echo "style='display:none'";} ?>>
								<td colspan="2" width="80%">
									<table width="98%" cellpadding="0" cellspacing="0" align="center" style="border:1px solid black;">
										<tr>
											<td class="Libelle" style="border:1px solid black;" width="10%" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Date accident at work";}else{echo "Date AT";} ?></td>
											<td class="Libelle" style="border:1px solid black;" width="25%" align="center">
												<?php if($_SESSION['Langue']=="EN"){echo "Injured name";}else{echo "Nom accidenté";} ?><br>
												<input type="checkbox" name="afficherLesPersonnes" id="afficherLesPersonnes" onclick="afficherToutesPersonnes()" />&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Excluding site";}else{echo "Hors prestation";} ?>
											</td>
											<td class="Libelle" style="border:1px solid black;" width="8%" align="center"><?php if($_SESSION['Langue']=="EN"){echo "With / Without stop";}else{echo "Avec/Sans arrêt";} ?></td>
											<td class="Libelle" style="border:1px solid black;" width="8%" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Trip accident";}else{echo "A. Trajet";} ?></td>
											<td class="Libelle" style="border:1px solid black;" width="49%" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Summary description of TA";}else{echo "Description synthétique de l'AT";} ?></td>
										</tr>
										<?php
											
											$ListeId= array(0,0,0,0);
											if($nbResultaMoisPresta>0){
												$req="SELECT Id, DateAT,Id_Personne,AvecArret,AccidentTrajet,DateRetour,AnalyseEffectuee,PieceJointe,Description
													FROM moris_moisprestation_securite 
													WHERE Suppr=0 
													AND Id_MoisPrestation=".$LigneMoisPrestation['Id']." ";
												$resultAT=mysqli_query($bdd,$req);
												$nbResultaAT=mysqli_num_rows($resultAT);
												if($nbResultaAT>0){
													$nb=0;
													while($rowAT=mysqli_fetch_array($resultAT)){
														if($nb<4){$ListeId[$nb]=$rowAT['Id'];}
														$nb++;
													}
												}	
											}

											for($i=1;$i<=4;$i++){
												$dateAT="";
												$Id_Accidente=0;
												$checkArret="";
												$checkTrajet="";
												$dateRetour="";
												$checkAnalyse="";
												$id=0;
												$PJ="";
												$description="";
												
												$req="SELECT Id, DateAT,Id_Personne,AvecArret,AccidentTrajet,DateRetour,AnalyseEffectuee,PieceJointe,Description
												FROM moris_moisprestation_securite 
												WHERE Suppr=0 
												AND Id=".$ListeId[$i-1]." ";
												$resultAT=mysqli_query($bdd,$req);
												$nbResultaAT=mysqli_num_rows($resultAT);
												if($nbResultaAT>0){
													$rowAT=mysqli_fetch_array($resultAT);
													$dateAT=AfficheDateFR($rowAT['DateAT']);
													$Id_Accidente=$rowAT['Id_Personne'];
													if($rowAT['AvecArret']==1){$checkArret="checked";}
													if($rowAT['AccidentTrajet']==1){$checkTrajet="checked";}
													$dateRetour=AfficheDateFR($rowAT['DateRetour']);
													if($rowAT['AnalyseEffectuee']==1){$checkAnalyse="checked";}
													$id=$rowAT['Id'];
													$PJ=$rowAT['PieceJointe'];
													$description=stripslashes($rowAT['Description']);
												}
												
												$req="SELECT Id, Nom, Prenom,
													IF((SELECT COUNT(Id_Personne)
														FROM new_competences_personne_prestation
														WHERE Date_Debut<='".date('Y-m-d')."'
														AND (Date_Fin<='0001-01-01' OR Date_Fin>='".date('Y-m-d')."')
														AND Id_Prestation=".$PrestationSelect."
														AND Id_Personne=new_rh_etatcivil.Id)>0,1,0) AS AppartientPresta
													FROM new_rh_etatcivil ";													
												$req.="ORDER BY Nom, Prenom;";
												$result=mysqli_query($bdd,$req);
												$nbResulta=mysqli_num_rows($result);
										?>
										<tr>
											<td style="border:1px solid black;" align="center">
												<input type="hidden" name="Id_AT<?php echo $i;?>" value="<?php echo $id;?>">
												<input type="date" id="dateAT<?php echo $i;?>" name="dateAT<?php echo $i;?>" size="3" style="text-align:center;" value="<?php echo $dateAT; ?>">
											</td>
											<td style="border:1px solid black;" align="center">
											<select id="personne<?php echo $i;?>" name="personne<?php echo $i;?>">
												<option name='0' value='0'></option>
												<?php 
													mysqli_data_seek($result,0);
													if ($nbResulta>0){
														while($row=mysqli_fetch_array($result)){
																$selected="";
																if($Id_Accidente==$row['Id']){$selected="selected";}
																
																$display="style='display:none;'";
																if($row['AppartientPresta']==1 || $Id_Accidente==$row['Id']){
																	$display="";
																}
																echo "<option id='".$row['AppartientPresta']."_".$row['Id']."' value='".$row['Id']."' ".$selected." ".$display." >".$row['Nom']." ".$row['Prenom']."</option>";
																
														}
													}
												?>
											</select>
											</td>
											<td style="border:1px solid black;" align="center"><input type="checkbox" name="avecArret<?php echo $i;?>" <?php echo $checkArret; ?> style="text-align:center;"></td>
											<td style="border:1px solid black;" align="center"><input type="checkbox" name="accidentTrajet<?php echo $i;?>" <?php echo $checkTrajet; ?> style="text-align:center;"></td>
											<td style="border:1px solid black;" align="center"><textarea id="description<?php echo $i;?>" name="description<?php echo $i;?>"  cols="100" rows="1" noresize="noresize"><?php echo $description; ?></textarea></td>
										</tr>
										<?php 
											}
										?>
									</table>
								</td>
							</tr>
							<tr <?php if($Ligne['SecuriteADesactive']==0 && $annee."_".$mois>"2023_06"){}else{echo "style='display:none'";} ?>>
								<td colspan="2" width="80%">
									<table width="98%" cellpadding="0" cellspacing="0" align="center" style="border:1px solid black;">
										<tr>
											<td class="Libelle" style="border:1px solid black;" width="10%" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Date accident at work";}else{echo "Date AT";} ?></td>
											<td class="Libelle" style="border:1px solid black;" width="25%" align="center">
												<?php if($_SESSION['Langue']=="EN"){echo "Injured name";}else{echo "Nom accidenté";} ?><br>
											</td>
											<td class="Libelle" style="border:1px solid black;" width="8%" align="center"><?php if($_SESSION['Langue']=="EN"){echo "With / Without stop";}else{echo "Avec/Sans arrêt";} ?></td>
											<td class="Libelle" style="border:1px solid black;" width="8%" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Trip accident";}else{echo "A. Trajet";} ?></td>
											<td class="Libelle" style="border:1px solid black;" width="49%" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Summary description of TA";}else{echo "Description synthétique de l'AT";} ?></td>
										</tr>
										<?php
											

											$req="SELECT Id, DateAT,
												(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne,
												ArretDeTravail AS AvecArret,
												IF(Id_Lieu_AT=4 OR Id_Lieu_AT=6,1,0) AS AccidentTrajet,
												CommentaireNature AS Description
												FROM rh_personne_at 
												WHERE Suppr=0 
												AND Id_Prestation=".$PrestationSelect."
												AND CONCAT(YEAR(DateAT),'_',IF(MONTH(DateAT)<10,CONCAT('0',MONTH(DateAT)),MONTH(DateAT)))='".$_SESSION['MORIS_Annee2']."_".$_SESSION['MORIS_Mois2']."' ";

											$resultAT=mysqli_query($bdd,$req);
											$nbResultaAT=mysqli_num_rows($resultAT);
											if($nbResultaAT>0){
												while($rowAT=mysqli_fetch_array($resultAT)){
													$description=stripslashes($rowAT['Description']);
										?>
													<tr>
														<td style="border:1px solid black;" align="center">
															<?php echo AfficheDateJJ_MM_AAAA($rowAT['DateAT']);?>
														</td>
														<td style="border:1px solid black;" align="center">
															<?php echo stripslashes($rowAT['Personne']);?>
														</td>
														<td style="border:1px solid black;" align="center">
														<?php 
															$avecArret="<img width='15px' src='../../Images/delete.png' border='0' />";
															if($rowAT['AvecArret']==1){
																$avecArret="<img width='15px' src='../../Images/tick.png' border='0' />";
															}
															echo $avecArret;
														?>
														</td>
														<td style="border:1px solid black;" align="center">
														<?php 
															$AccidentTrajet="<img width='15px' src='../../Images/delete.png' border='0' />";
															if($rowAT['AccidentTrajet']==1){
																$AccidentTrajet="<img width='15px' src='../../Images/tick.png' border='0' />";
															}
															echo $AccidentTrajet;
														?>
														</td>
														<td style="border:1px solid black;" align="center">
															<?php echo stripslashes($rowAT['Description']);?>
														</td>
													</tr>
										<?php 
												}
											}
										?>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td align="center">
			<table <?php if($Ligne['PRMADesactive']==1){echo "style='display:none'";} ?> width="100%" cellpadding="0" cellspacing="0">			
				<tr>
					<td width="100%" height="100%">
						<?php 
							$req="SELECT FR,EN
							FROM moris_aideparagraphe
							WHERE moris_aideparagraphe.NomParagraphe='PRM' ";
							$resultAide=mysqli_query($bdd,$req);
							$LigneAide=mysqli_fetch_array($resultAide);
						?>
						<table class="TableCompetences" width="99%" height="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td class="Libelle" height="5%" colspan="2" style="font-size:15px;"><?php if($_SESSION['Langue']=="EN"){echo "PRM & CUSTOMER SATISFACTION";}else{echo "PRM & SATISFACTION CLIENTS";} ?></td>
								<td style="cursor:pointer;" align="right"><div onclick="AfficheAide('Prm')"><img id="Prm" width="30px" src='../../Images/Aide2.png' border='0' /></div></td>
							</tr>
							<tr class="Prm" style="display:none;"><td height="4"></td></tr>
							<tr class="Prm" style="display:none;"><td bgcolor="#d5f2ff" style="border-top:2px dotted #0b6acb;" colspan="2" height="4"></td></tr>
							<tr class="Prm" style="display:none;">
								<td colspan="2" bgcolor="#d5f2ff" style="color:#000000;">
								<?php if($_SESSION['Langue']=="EN"){echo nl2br(str_replace("\\","",stripslashes($LigneAide['EN'])));}else{echo nl2br(str_replace("\\","",stripslashes($LigneAide['FR'])));} ?>
								</td>
							</tr>
							<tr><td style="border-bottom:2px solid #0b6acb;" colspan="4" height="4"></td></tr>
							<tr>
								<td align='left'>
								</td>
								<td align='right'>
									<?php 
										$checkedWP="checked";
										$checkedAT="";
										$AT=0;
										if($nbResultaMoisPresta>0){
											if($LigneMoisPrestation['FormatAT']==1){
												$checkedWP="";
												$checkedAT="checked";
												$AT=1;
											}
											else{
												$checkedWP="checked";
												$checkedAT="";
												$AT=0;
											}
										}
										elseif($nbResultaMoisPrestaM1>0){
											if($LigneMoisPrestationM1['FormatAT']==1){
												$checkedWP="";
												$checkedAT="checked";
												$AT=1;
											}
											else{
												$checkedWP="checked";
												$checkedAT="";
												$AT=0;
											}
										}
									?>
									<table>
										<tr><td><input type="radio" name="formatSatisfaction" id="formatSatisfaction" onclick="changerFormat(0)" value="0" <?php echo $checkedWP; ?>/>Format WP</td></tr>
										<tr><td><input type="radio" name="formatSatisfaction" id="formatSatisfaction" onclick="changerFormat(1)" value="1" <?php echo $checkedAT; ?>/>Format AT</td></tr>
									</table>
								</td>
							</tr>
							<tr>
								<td width="20%" valign="top">
									<table width="98%" cellpadding="0" cellspacing="0" align="left">
										<tr>
											<td class="Libelle" style="border:1px solid black;border-top:2px solid black;border-left:2px solid black;border-right:2px solid black;" width="15%" align="center" colspan="2"><?php if($_SESSION['Langue']=="EN"){echo "Progress Review Meeting";}else{echo "Réunion de suivi des progrès";} ?></td>
										</tr>
										<tr>
											<td class="Libelle" style="border:1px solid black;border-left:2px solid black;" width="15%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Last PRM";}else{echo "Dernière PRM";} ?></td>
											<td style="border:1px solid black;border-right:2px solid black;" width="15%" align="center" >
												<input type="date" name="derniereDatePRM" id="derniereDatePRM" size="10" style="text-align:center;" value="<?php if($nbResultaMoisPresta>0){echo AfficheDateFR($LigneMoisPrestation['DerniereDatePRM']);}elseif($nbResultaMoisPrestaM1>0){echo stripslashes($LigneMoisPrestationM1['DerniereDatePRM']);} ?>">
												<input type="date" name="derniereDatePRMM1" id="derniereDatePRMM1" size="10" style="text-align:center;display:none;" value="<?php if($nbResultaMoisPrestaM1>0){echo AfficheDateFR($LigneMoisPrestationM1['DerniereDatePRM']);}?>">
											</td>
										</tr>
										<tr>
											<td class="Libelle" style="border:1px solid black;border-bottom:2px solid black;border-left:2px solid black;" width="15%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Periodicity";}else{echo "Périodicité";} ?></td>
											<td style="border:1px solid black;border-right:2px solid black;border-bottom:2px solid black;" width="15%" align="center">
												<select name="periodicitePRM" id="periodicitePRM">
													<option value=""></option>
													<option value="Hebdomadaire" <?php if($nbResultaMoisPresta>0){if($LigneMoisPrestation['PeriodicitePRM']=="Hebdomadaire"){echo "selected";}}elseif($nbResultaMoisPrestaM1>0){if($LigneMoisPrestationM1['PeriodicitePRM']=="Hebdomadaire"){echo "selected";}} ?>><?php if($_SESSION['Langue']=="EN"){echo "Weekly";}else{echo "Hebdomadaire";} ?></option>
													<option value="Bi Hebdomadaire" <?php if($nbResultaMoisPresta>0){if($LigneMoisPrestation['PeriodicitePRM']=="Bi Hebdomadaire"){echo "selected";}}elseif($nbResultaMoisPrestaM1>0){if($LigneMoisPrestationM1['PeriodicitePRM']=="Bi Hebdomadaire"){echo "selected";}} ?>><?php if($_SESSION['Langue']=="EN"){echo "Bi Weekly";}else{echo "Bi Hebdomadaire";} ?></option>
													<option value="Mensuel" <?php if($nbResultaMoisPresta>0){if($LigneMoisPrestation['PeriodicitePRM']=="Mensuel"){echo "selected";}}elseif($nbResultaMoisPrestaM1>0){if($LigneMoisPrestationM1['PeriodicitePRM']=="Mensuel"){echo "selected";}} ?>><?php if($_SESSION['Langue']=="EN"){echo "Monthly";}else{echo "Mensuel";} ?></option>
													<option value="Bi Mensuel" <?php if($nbResultaMoisPresta>0){if($LigneMoisPrestation['PeriodicitePRM']=="Bi Mensuel"){echo "selected";}}elseif($nbResultaMoisPrestaM1>0){if($LigneMoisPrestationM1['PeriodicitePRM']=="Bi Mensuel"){echo "selected";}} ?>><?php if($_SESSION['Langue']=="EN"){echo "Bi Monthly";}else{echo "Bi Mensuel";} ?></option>
													<option value="Trimestriel" <?php if($nbResultaMoisPresta>0){if($LigneMoisPrestation['PeriodicitePRM']=="Trimestriel"){echo "selected";}}elseif($nbResultaMoisPrestaM1>0){if($LigneMoisPrestationM1['PeriodicitePRM']=="Trimestriel"){echo "selected";}} ?>><?php if($_SESSION['Langue']=="EN"){echo "Quaterly";}else{echo "Trimestriel";} ?></option>
													<option value="Semestriel" <?php if($nbResultaMoisPresta>0){if($LigneMoisPrestation['PeriodicitePRM']=="Semestriel"){echo "selected";}}elseif($nbResultaMoisPrestaM1>0){if($LigneMoisPrestationM1['PeriodicitePRM']=="Semestriel"){echo "selected";}} ?>><?php if($_SESSION['Langue']=="EN"){echo "Semestrial";}else{echo "Semestriel";} ?></option>
													<option value="Pas de PRM" <?php if($nbResultaMoisPresta>0){if($LigneMoisPrestation['PeriodicitePRM']=="Pas de PRM"){echo "selected";}}elseif($nbResultaMoisPrestaM1>0){if($LigneMoisPrestationM1['PeriodicitePRM']=="Pas de PRM"){echo "selected";}} ?>><?php if($_SESSION['Langue']=="EN"){echo "No PRM";}else{echo "Pas de PRM";} ?></option>
												</select>
												<input name="periodicitePRMM1" id="periodicitePRMM1" size="3" style="text-align:center;display:none;" value="<?php if($nbResultaMoisPrestaM1>0){echo $LigneMoisPrestationM1['PeriodicitePRM'];}?>">
											</td>
										</tr>
									</table>
								</td>
								<td width="80%" valign="top">
									<table width="98%" cellpadding="0" cellspacing="0" align="left">
										<tr>
											<td style="border:1px solid #ffffff;border-top:2px solid #ffffff;border-left:2px solid #ffffff;border-right:2px solid #ffffff;">&nbsp;</td>
										</tr>
										<tr>
											<td style="border:1px solid #ffffff;border-top:2px solid #ffffff;border-left:2px solid #ffffff;border-right:2px solid #ffffff;" valign="center">
												<?php 
												if($nbResultaMoisPresta>0){
													if($LigneMoisPrestation['PieceJointeDernierePRM']<>"")
													{
														echo '<a class="Info" href="'.$chemin."/".$DirFichierPRM.$LigneMoisPrestation['PieceJointeDernierePRM'].'" target="_blank"><img width="20px" src="../../Images/Trombone.png" border="0" /></a>';
														echo '<input type="hidden" name="fichieractuelPRM" value="'.$LigneMoisPrestation['PieceJointeDernierePRM'].'">';
														if($_SESSION["Langue"]=="EN"){
															echo '<input type="checkbox" name="SupprFichierPRM" onClick="CheckFichierPRM();">Delete file';
														}
														else{
															echo '<input type="checkbox" name="SupprFichierPRM" onClick="CheckFichierPRM();">Supprimer le fichier';
														}
														echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
													}
												}
												?>
												<input name="fichierPRM" type="file" onChange="CheckFichierPRM();">
											</td>
										</tr>
										<tr>
											<td>&nbsp;</td>
										</tr>
										<tr>
											<td>&nbsp;</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td>
								</td>
								<td>
									<table width="99%" cellpadding="0" cellspacing="0" align="left">
										<tr>
											<td class="Libelle" style="border:1px solid black;border-top:2px solid black;border-left:2px solid black;" width="10%" align="center"><div id="Q1"><?php if($_SESSION['Langue']=="EN"){echo "Product quality / performance / consideration of cutomer requirements";}else{echo "Qualité des produits / performance / prise en compte des exigences du client";} ?></div></td>
											<td class="Libelle" style="border:1px solid black;border-top:2px solid black;" width="10%" align="center"><div id="Q2"><?php if($_SESSION['Langue']=="EN"){echo "Within alloted time";}else{echo "Dans le temps imparti";} ?></div></td>
											<td class="Libelle" style="border:1px solid black;border-top:2px solid black;" width="10%" align="center"><div id="Q3"><?php if($_SESSION['Langue']=="EN"){echo "Personnel abilities";}else{echo "Capacités du personnel";} ?></div></td>
											<td class="Libelle" style="border:1px solid black;border-top:2px solid black;" width="10%" align="center"><div id="Q4"><?php if($_SESSION['Langue']=="EN"){echo "Autonomy / flexibility";}else{echo "Autonomie / Flexibilité";} ?></div></td>
											<td class="Libelle" style="border:1px solid black;border-top:2px solid black;" width="10%" align="center"><div id="Q5"><?php if($_SESSION['Langue']=="EN"){echo "Proactivity / anticipation";}else{echo "Proactivité / anticipation";} ?></div></td>
											<td class="Libelle" style="border:1px solid black;border-top:2px solid black;" width="10%" align="center"><div id="Q6"><?php if($_SESSION['Langue']=="EN"){echo "Interface / Communication with the customer";}else{echo "Interface / communication avec le client";} ?></div></td>
											<td class="Libelle" style="border:1px solid black;border-top:2px solid black;border-right:2px solid black;" width="10%" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Average";}else{echo "Moyenne";} ?></td>
											<td width="30%"></td>
										</tr>
										<tr>
											<td style="border:1px solid black;border-bottom:2px solid black;border-left:2px solid black;" align="center">
												<input class="Bouton" type="button" style="font-size:10px;" name="NAEval" onClick="document.getElementById('evaluationQualite').value='NA';calculMoyennePRM();" value="NA">
												<input onKeyUp="nombreEval(this)" oninput="calculMoyennePRM()" type="texte" name="evaluationQualite" id="evaluationQualite" size="5" style="text-align:center;" value="<?php if($nbResultaMoisPresta>0){echo $LigneMoisPrestation['EvaluationQualite'];}elseif($nbResultaMoisPrestaM1>0){echo stripslashes($LigneMoisPrestationM1['EvaluationQualite']);}?>">
												<input type="texte" name="evaluationQualiteM1" id="evaluationQualiteM1" size="5" style="text-align:center;display:none;" value="<?php if($nbResultaMoisPrestaM1>0){echo $LigneMoisPrestationM1['EvaluationQualite'];}?>">
											</td>
											<td style="border:1px solid black;border-bottom:2px solid black;" align="center">
												<input class="Bouton" type="button" style="font-size:10px;" name="NAEval" onClick="document.getElementById('evaluationDelais').value='NA';calculMoyennePRM();" value="NA">
												<input onKeyUp="nombreEval(this)" oninput="calculMoyennePRM()" type="texte" name="evaluationDelais" id="evaluationDelais" size="5" style="text-align:center;" value="<?php if($nbResultaMoisPresta>0){echo $LigneMoisPrestation['EvaluationDelais'];}elseif($nbResultaMoisPrestaM1>0){echo stripslashes($LigneMoisPrestationM1['EvaluationDelais']);}?>">
												<input type="texte" name="evaluationDelaisM1" id="evaluationDelaisM1" size="5" style="text-align:center;display:none;" value="<?php if($nbResultaMoisPrestaM1>0){echo $LigneMoisPrestationM1['EvaluationDelais'];}?>">
											</td>
											<td style="border:1px solid black;border-bottom:2px solid black;" align="center">
												<input class="Bouton" type="button" style="font-size:10px;" name="NAEval" onClick="document.getElementById('evaluationCompetencePersonnel').value='NA';calculMoyennePRM();" value="NA">
												<input onKeyUp="nombreEval(this)" oninput="calculMoyennePRM()" type="texte" name="evaluationCompetencePersonnel" id="evaluationCompetencePersonnel" size="5" style="text-align:center;" value="<?php if($nbResultaMoisPresta>0){echo $LigneMoisPrestation['EvaluationCompetencePersonnel'];}elseif($nbResultaMoisPrestaM1>0){echo stripslashes($LigneMoisPrestationM1['EvaluationCompetencePersonnel']);}?>">
												<input type="texte" name="evaluationCompetencePersonnelM1" id="evaluationCompetencePersonnelM1" size="5" style="text-align:center;display:none;" value="<?php if($nbResultaMoisPrestaM1>0){echo $LigneMoisPrestationM1['EvaluationCompetencePersonnel'];}?>">
											</td>
											<td style="border:1px solid black;border-bottom:2px solid black;" align="center">
												<input class="Bouton" type="button" style="font-size:10px;" name="NAEval" onClick="document.getElementById('evaluationAutonomie').value='NA';calculMoyennePRM();" value="NA">
												<input onKeyUp="nombreEval(this)" oninput="calculMoyennePRM()" type="texte" name="evaluationAutonomie" id="evaluationAutonomie" size="5" style="text-align:center;" value="<?php if($nbResultaMoisPresta>0){echo $LigneMoisPrestation['EvaluationAutonomie'];}elseif($nbResultaMoisPrestaM1>0){echo stripslashes($LigneMoisPrestationM1['EvaluationAutonomie']);}?>">
												<input type="texte" name="evaluationAutonomieM1" id="evaluationAutonomieM1" size="5" style="text-align:center;display:none;" value="<?php if($nbResultaMoisPrestaM1>0){echo $LigneMoisPrestationM1['EvaluationAutonomie'];}?>">
											</td>
											<td style="border:1px solid black;border-bottom:2px solid black;" align="center">
												<input class="Bouton" type="button" style="font-size:10px;" name="NAEval" onClick="document.getElementById('evaluationAnticipation').value='NA';calculMoyennePRM();" value="NA">
												<input onKeyUp="nombreEval(this)" oninput="calculMoyennePRM()" type="texte" name="evaluationAnticipation" id="evaluationAnticipation" size="5" style="text-align:center;" value="<?php if($nbResultaMoisPresta>0){echo $LigneMoisPrestation['EvaluationAnticipation'];}elseif($nbResultaMoisPrestaM1>0){echo stripslashes($LigneMoisPrestationM1['EvaluationAnticipation']);}?>">
												<input type="texte" name="evaluationAnticipationM1" id="evaluationAnticipationM1" size="5" style="text-align:center;display:none;" value="<?php if($nbResultaMoisPrestaM1>0){echo $LigneMoisPrestationM1['EvaluationAnticipation'];}?>">
											</td>
											<td style="border:1px solid black;border-bottom:2px solid black;" align="center">
												<input class="Bouton" type="button" style="font-size:10px;" name="NAEval" onClick="document.getElementById('evaluationCommunication').value='NA';calculMoyennePRM();" value="NA">
												<input onKeyUp="nombreEval(this)" oninput="calculMoyennePRM()" type="texte" name="evaluationCommunication" id="evaluationCommunication" size="5" style="text-align:center;" value="<?php if($nbResultaMoisPresta>0){echo $LigneMoisPrestation['EvaluationCommunication'];}elseif($nbResultaMoisPrestaM1>0){echo stripslashes($LigneMoisPrestationM1['EvaluationCommunication']);}?>">
												<input type="texte" name="evaluationCommunicationM1" id="evaluationCommunicationM1" size="5" style="text-align:center;display:none;" value="<?php if($nbResultaMoisPrestaM1>0){echo $LigneMoisPrestationM1['EvaluationCommunication'];}?>">
											</td>
											<td style="border:1px solid black;border-bottom:2px solid black;border-right:2px solid black;" align="center">
												<?php 
												$moyenne="";
												if($nbResultaMoisPresta>0)
												{
													$total=0;
													$nbEval=0;
													if($LigneMoisPrestation['EvaluationQualite']<>"NA"){
														$total+=$LigneMoisPrestation['EvaluationQualite'];
														$nbEval++;
													}
													if($LigneMoisPrestation['EvaluationDelais']<>"NA"){
														$total+=$LigneMoisPrestation['EvaluationDelais'];
														$nbEval++;
													}
													if($LigneMoisPrestation['EvaluationCompetencePersonnel']<>"NA"){
														$total+=$LigneMoisPrestation['EvaluationCompetencePersonnel'];
														$nbEval++;
													}
													if($LigneMoisPrestation['EvaluationAutonomie']<>"NA"){
														$total+=$LigneMoisPrestation['EvaluationAutonomie'];
														$nbEval++;
													}
													if($LigneMoisPrestation['EvaluationAnticipation']<>"NA"){
														$total+=$LigneMoisPrestation['EvaluationAnticipation'];
														$nbEval++;
													}
													if($LigneMoisPrestation['EvaluationCommunication']<>"NA"){
														$total+=$LigneMoisPrestation['EvaluationCommunication'];
														$nbEval++;
													}
													if($nbEval>0){
														$moyenne=round($total/$nbEval,2);
													}
												}
												elseif($nbResultaMoisPrestaM1>0){
													$total=0;
													$nbEval=0;
													if($LigneMoisPrestationM1['EvaluationQualite']<>"NA"){
														$total+=$LigneMoisPrestationM1['EvaluationQualite'];
														$nbEval++;
													}
													if($LigneMoisPrestationM1['EvaluationDelais']<>"NA"){
														$total+=$LigneMoisPrestationM1['EvaluationDelais'];
														$nbEval++;
													}
													if($LigneMoisPrestationM1['EvaluationCompetencePersonnel']<>"NA"){
														$total+=$LigneMoisPrestationM1['EvaluationCompetencePersonnel'];
														$nbEval++;
													}
													if($LigneMoisPrestationM1['EvaluationAutonomie']<>"NA"){
														$total+=$LigneMoisPrestationM1['EvaluationAutonomie'];
														$nbEval++;
													}
													if($LigneMoisPrestationM1['EvaluationAnticipation']<>"NA"){
														$total+=$LigneMoisPrestationM1['EvaluationAnticipation'];
														$nbEval++;
													}
													if($LigneMoisPrestationM1['EvaluationCommunication']<>"NA"){
														$total+=$LigneMoisPrestationM1['EvaluationCommunication'];
														$nbEval++;
													}
													if($nbEval>0){
														$moyenne=round($total/$nbEval,2);
													}
												}
												?>
												<div id="moyennePRM">
													<?php echo $moyenne;?>
												</div>
											</td>
											<td>
												<?php 
												if($nbResultaMoisPresta>0){
													if($LigneMoisPrestation['PieceJointeSatisfactionPRM']<>"")
													{
														echo '<a class="Info" href="'.$chemin."/".$DirFichierSatisfactionClient.$LigneMoisPrestation['PieceJointeSatisfactionPRM'].'" target="_blank"><img width="20px" src="../../Images/Trombone.png" border="0" /></a>';
														echo '<input type="hidden" name="fichieractuelSatisfactionClient" value="'.$LigneMoisPrestation['PieceJointeSatisfactionPRM'].'">';
														if($_SESSION["Langue"]=="EN"){
															echo '<input type="checkbox" name="SupprFichierSatisfactionClient" onClick="CheckFichierSatisfactionClient();">Delete file';
														}
														else{
															echo '<input type="checkbox" name="SupprFichierSatisfactionClient" onClick="CheckFichierSatisfactionClient();">Supprimer le fichier';
														}
														echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
													}
												}
												?>
												<input name="fichierSatisfactionClient" type="file" onChange="CheckFichierSatisfactionClient();">
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td></td>
								<td>
									<table style="width:100%; align:center;">
										<?php 
											$styleidPlusSatisfaction="";
											$styleidPlusSatisfaction2="style='display:none;'";
											
											if($nbResultaMoisPresta>0){
												if($LigneMoisPrestation['PointFortSatisfaction']<>"" || $LigneMoisPrestation['PointFaibleSatisfaction']<>"" || $LigneMoisPrestation['CommentaireSatisfaction']<>""){
													$styleidPlusSatisfaction2="";
													$styleidPlusSatisfaction="style='display:none;'";
												}
											}
											elseif($nbResultaMoisPrestaM1>0){
												if($LigneMoisPrestationM1['PointFortSatisfaction']<>"" || $LigneMoisPrestationM1['PointFaibleSatisfaction']<>"" || $LigneMoisPrestationM1['CommentaireSatisfaction']<>""){
													$styleidPlusSatisfaction2="";
													$styleidPlusSatisfaction="style='display:none;'";
												}
											}
										?>
										<tr>
											<td <?php echo $styleidPlusSatisfaction; ?> class="Libelle" id="idPlusSatisfaction">
												<img style="cursor:pointer;" id="BtnPlus" width='30px' src='../../Images/add.png' onclick="AfficherSatisfaction()" border="0" >
												<?php if($_SESSION['Langue']=="EN"){echo "Strengths / Weaknesses / Comments";}else{echo "Points Forts / Points Faibles / Commentaires";} ?>
											</td>
										</tr>
										<tr>
											<td <?php echo $styleidPlusSatisfaction2; ?> id="idPlusSatisfaction2">
												<table style="width:80%; align:center;">
													<tr>
														<td class="Libelle" align="left" width="50%"><?php if($_SESSION['Langue']=="EN"){echo "Strengths";}else{echo "Points forts";} ?></td>
														<td class="Libelle" align="left" width="50%"><?php if($_SESSION['Langue']=="EN"){echo "Weaknesses";}else{echo "Points faibles";} ?></td>
													</tr>
													<tr>
														<td class="Libelle" align="left" width="50%">
															<textarea name="pointFort" id="pointFort" cols="80" rows="3" noresize="noresize"><?php if($nbResultaMoisPresta>0){echo stripslashes($LigneMoisPrestation['PointFortSatisfaction']);}elseif($nbResultaMoisPrestaM1>0){echo stripslashes($LigneMoisPrestationM1['PointFortSatisfaction']);} ?></textarea>
														</td>
														<td class="Libelle" align="left" width="50%">
															<textarea name="pointFaible" id="pointFaible" cols="80" rows="3" noresize="noresize"><?php if($nbResultaMoisPresta>0){echo stripslashes($LigneMoisPrestation['PointFaibleSatisfaction']);}elseif($nbResultaMoisPrestaM1>0){echo stripslashes($LigneMoisPrestationM1['PointFaibleSatisfaction']);} ?></textarea>
														</td>
													</tr>
													<tr>
														<td class="Libelle" align="left" colspan="2"><?php if($_SESSION['Langue']=="EN"){echo "Comments";}else{echo "Commentaires";} ?></td>
													</tr>
													<tr>
														<td class="Libelle" align="left" colspan="2">
															<textarea name="CommentaireSatisfaction" id="CommentaireSatisfaction" cols="80" rows="3" noresize="noresize"><?php if($nbResultaMoisPresta>0){echo stripslashes($LigneMoisPrestation['CommentaireSatisfaction']);}elseif($nbResultaMoisPrestaM1>0){echo stripslashes($LigneMoisPrestationM1['CommentaireSatisfaction']);} ?></textarea>
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td></td>
								<td>
									<table width="27%" cellpadding="0" cellspacing="0" align="left" style="border:1px solid black;">
										<tr>
											<td class="Libelle" style="border:1px solid black;" width="50%" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Date of sending of the last request for satisfaction to the originator";}else{echo "Date d'envoi de la dernière demande de satisfaction au donneur d'ordre";} ?></td>
											<td class="Libelle" style="border:1px solid black;" width="50%" align="center">
												<input type="date" name="dateEnvoiDemandeSatisfaction" id="dateEnvoiDemandeSatisfaction" size="10" style="text-align:center;<?php if($nbResultaMoisPresta>0){if($LigneMoisPrestation['DateEnvoiDemandeSatisfaction']<date('Y-m-d',strtotime(date('Y-m-d')." -6 month"))){echo "background-color:#f33535;";}} ?>" value="<?php if($nbResultaMoisPresta>0){echo AfficheDateFR($LigneMoisPrestation['DateEnvoiDemandeSatisfaction']);}elseif($nbResultaMoisPrestaM1>0){echo AfficheDateFR($LigneMoisPrestationM1['DateEnvoiDemandeSatisfaction']);}?>">
												<input type="date" name="dateEnvoiDemandeSatisfactionM1" id="dateEnvoiDemandeSatisfactionM1" size="10" style="text-align:center;display:none;" value="<?php if($nbResultaMoisPrestaM1>0){echo AfficheDateFR($LigneMoisPrestationM1['DateEnvoiDemandeSatisfaction']);}?>">
											</td>
										</tr>
										<tr>
											<td class="Libelle" style="border:1px solid black;" width="50%" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Last evaluation date";}else{echo "Dernière date d'évaluation";} ?></td>
											<td class="Libelle" style="border:1px solid black;" width="50%" align="center">
												<input type="date" name="derniereDateEvaluation" id="derniereDateEvaluation" size="10" style="text-align:center;<?php if($nbResultaMoisPresta>0){if($LigneMoisPrestation['DerniereDateEvaluation']<date('Y-m-d',strtotime(date('Y-m-d')." -6 month"))){echo "background-color:#f33535;";}} ?>" value="<?php if($nbResultaMoisPresta>0){echo AfficheDateFR($LigneMoisPrestation['DerniereDateEvaluation']);}elseif($nbResultaMoisPrestaM1>0){echo AfficheDateFR($LigneMoisPrestationM1['DerniereDateEvaluation']);}?>">
												<input type="date" name="derniereDateEvaluationM1" id="derniereDateEvaluationM1" size="10" style="text-align:center;display:none;" value="<?php if($nbResultaMoisPrestaM1>0){echo AfficheDateFR($LigneMoisPrestationM1['DerniereDateEvaluation']);}?>">
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td align="center">
			<table <?php if($Ligne['NCADesactive']==1){echo "style='display:none'";} ?> width="100%" cellpadding="0" cellspacing="0">			
				<tr>
					<td width="100%" height="100%">
						<?php 
							$req="SELECT FR,EN
							FROM moris_aideparagraphe
							WHERE moris_aideparagraphe.NomParagraphe='NC' ";
							$resultAide=mysqli_query($bdd,$req);
							$LigneAide=mysqli_fetch_array($resultAide);
						?>
						<table class="TableCompetences" width="99%" height="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td class="Libelle" height="5%" style="font-size:15px;"><?php if($_SESSION['Langue']=="EN"){echo "NC & RC NEWS";}else{echo "NOUVELLES NC & RC";} ?></td>
								<td style="cursor:pointer;" align="right"><div onclick="AfficheAide('Nc')"><img id="Nc" width="30px" src='../../Images/Aide2.png' border='0' /></div></td>
							</tr>
							<tr class="Nc" style="display:none;"><td height="4"></td></tr>
							<tr class="Nc" style="display:none;"><td bgcolor="#d5f2ff" style="border-top:2px dotted #0b6acb;" colspan="2" height="4"></td></tr>
							<tr class="Nc" style="display:none;">
								<td colspan="2" bgcolor="#d5f2ff" style="color:#000000;">
								<?php if($_SESSION['Langue']=="EN"){echo nl2br(str_replace("\\","",stripslashes($LigneAide['EN'])));}else{echo nl2br(str_replace("\\","",stripslashes($LigneAide['FR'])));} ?>
								</td>
							</tr>
							<tr><td style="border-bottom:2px solid #0b6acb;" colspan="2" height="4"></td></tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td valign="top" colspan="2">
									<table width="98%" cellpadding="0" cellspacing="0" align="center" style="border:1px solid black;">
										<tr>
											<td class="Libelle" style="border:1px solid black;" width="20%" align="center"><?php if($_SESSION['Langue']=="EN"){echo "NC / RC";}else{echo "NC / RC";} ?></td>
											<td class="Libelle" style="border:1px solid black;" width="20%" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Ref";}else{echo "Ref";} ?></td>
											<td class="Libelle" style="border:1px solid black;" width="15%" align="center"><?php if($_SESSION['Langue']=="EN"){echo "CN creation date";}else{echo "Date de création de la NC";} ?></td>
											<td class="Libelle" style="border:1px solid black;" width="40%" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Synthetic description";}else{echo "Description synthétique";} ?></td>
										</tr>
										<?php
											
											$ListeId= array(0,0,0,0,0,0,0,0,0,0);
											if($nbResultaMoisPresta>0){
												$req="SELECT Id, Ref,Progression,Deadline,Commentaire,PieceJointe,NC_DAC,DateCreationNC,FicheAT
													FROM moris_moisprestation_ncdac
													WHERE Suppr=0 
													AND Progression=0
													AND NC_DAC<>'DAC'
													AND Id_MoisPrestation=".$LigneMoisPrestation['Id']." ";
												$resultNC=mysqli_query($bdd,$req);
												$nbResultaNC=mysqli_num_rows($resultNC);
												if($nbResultaNC>0){
													$nb=0;
													while($rowNC=mysqli_fetch_array($resultNC)){
													?>
													<tr>
														<td style="border:1px solid black;" <?php if($rowNC['FicheAT']<>""){echo "bgcolor='#e5e9b7'";} ?> align="center">
															<?php 
																$ncDAC=$rowNC['NC_DAC'];
																if($ncDAC=="NC" || $ncDAC==""){echo "NC Niv 1";}
																elseif($ncDAC=="NC Niv 2"){echo "NC Niv 2";}
																elseif($ncDAC=="NC Niv 3"){echo "NC Niv 3";}
																elseif($ncDAC=="RC"){echo "RC";}
															?>
														</td>
														<td style="border:1px solid black;" align="center">
															<?php echo $rowNC['FicheAT'];?>
														</td>
														<td style="border:1px solid black;" align="center">
															<?php echo AfficheDateJJ_MM_AAAA($rowNC['DateCreationNC']);?>
														</td>
														<td style="border:1px solid black;" align="center">
															<?php echo str_replace("\\","",stripslashes($rowNC['Commentaire']));?>
														</td>
													</tr>
													<?php
													}
												}	
											}
										?>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td colspan="2" align="right">
			<?php 
			if($_SESSION['MORIS_Annee2']>2021){
				if($nbResultaMoisPresta==0 || ($nbResultaMoisPresta>0 & $LigneMoisPrestation['Verouillage']==0)){
					if(DroitsFormationPrestationV2(array($PrestationSelect),array(2,3,4,46)) || (mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Id FROM moris_administrateur WHERE Id_Personne=".$IdPersonneConnectee))>0)){
				?>
				<input class="Bouton" type="submit" style="font-size:15px;" name="Btn_Enregistrer" onClick="calculOTD2();calculOQD2();return VerifChampsEnregistrement('<?php echo $_SESSION['Langue'];?>');" value="<?php if($_SESSION['Langue']=="EN"){echo "Save";}else{echo "Enregistrer";}?>">&nbsp;&nbsp;&nbsp;&nbsp;
				<input class="Bouton" type="submit" style="font-size:15px;" name="Btn_Verrouiller" onclick="return VerifChamps('<?php echo $_SESSION['Langue'];?>');" value="<?php if($_SESSION['Langue']=="EN"){echo "Lock";}else{echo "Verrouiller";}?>">&nbsp;&nbsp;&nbsp;&nbsp;
				<?php
					}
				}
				else{
					if(DroitsFormationPrestationV2(array($PrestationSelect),array(4))){
				?>
				<input class="Bouton" type="submit" style="font-size:15px;" name="Btn_Deverrouiller" onClick="calculOTD2();calculOQD2();" value="<?php if($_SESSION['Langue']=="EN"){echo "Unlock";}else{echo "Déverrouiller";}?>">&nbsp;&nbsp;&nbsp;&nbsp;
				<?php		
						
					}
					elseif((mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Id FROM moris_administrateur WHERE Id_Personne=".$IdPersonneConnectee))>0)){
				?>
				<input class="Bouton" type="submit" style="font-size:15px;" name="Btn_Enregistrer" onClick="calculOTD2();calculOQD2();return VerifChampsEnregistrement('<?php echo $_SESSION['Langue'];?>');" value="<?php if($_SESSION['Langue']=="EN"){echo "Save";}else{echo "Enregistrer";}?>">&nbsp;&nbsp;&nbsp;&nbsp;
				<input class="Bouton" type="submit" style="font-size:15px;" name="Btn_Deverrouiller" onClick="calculOTD2();calculOQD2();return VerifChampsEnregistrement('<?php echo $_SESSION['Langue'];?>');" value="<?php if($_SESSION['Langue']=="EN"){echo "Unlock";}else{echo "Déverrouiller";}?>">&nbsp;&nbsp;&nbsp;&nbsp;
				<?php
					}
				}
			}
			?>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
</table>
<?php 
	echo "<script>changerFormat('".$AT."');</script>";
	echo "<script>calculOTD2();</script>";
	echo "<script>calculOQD2();</script>";
	echo "<script>CalculerTotalCharge();</script>";
	
	
	if($Pdp==1){
		if($laDateValidite<date('Y-m-d')){
			if($_SESSION['Langue']=="EN"){
				echo "<script>alert(\"The validity date of the prevention plan has passed or has not been specified !\")</script>";
			}
			else{
				echo "<script>alert(\"La date de validité du plan de prévention est dépassé ou non renseigné !\")</script>";
			}
		}
		elseif($laDateValidite<date('Y-m-d',strtotime(date('Y-m-d')." +1 month"))){
			if($_SESSION['Langue']=="EN"){
				echo "<script>alert(\"The validity date of the prevention plan expires in less than a month\")</script>";
			}
			else{
				echo "<script>alert(\"La date de validité du plan de prévention expire dans moins d'un mois\")</script>";
			}
		}
	}
?>
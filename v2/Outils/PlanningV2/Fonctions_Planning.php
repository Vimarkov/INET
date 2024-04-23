<?php 
function unNombreSinon0_($leNombre){
	$nb=0;
	if($leNombre<>""){$nb=$leNombre;}
	return $nb;
}

//Renvoi le code planning si c'est un jour fixe 
function estJour_Fixe($DateJour,$Id_Personne){
	global $bdd;
	$EstFixe = "";
	
	$req="SELECT rh_jourfixe.Id, CodePlanning 
		FROM rh_jourfixe 
		LEFT JOIN rh_typeabsence
		ON rh_jourfixe.Id_TypeAbsence=rh_typeabsence.Id
		WHERE DateJour='".$DateJour."' 
		AND rh_jourfixe.Suppr=0 
		AND Id_Plateforme IN (
			SELECT Id_Plateforme 
			FROM rh_personne_mouvement
			LEFT JOIN new_competences_prestation
			ON rh_personne_mouvement.Id_Prestation=new_competences_prestation.Id
			WHERE rh_personne_mouvement.DateDebut<='".$DateJour."'
			AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$DateJour."')
			AND rh_personne_mouvement.EtatValidation=1 
			AND rh_personne_mouvement.Id_Personne=".$Id_Personne."
		) 
		AND (Id_Prestation=0 OR Id_Prestation IN (
				SELECT Id_Prestation 
				FROM rh_personne_mouvement
				LEFT JOIN new_competences_prestation
				ON rh_personne_mouvement.Id_Prestation=new_competences_prestation.Id
				WHERE rh_personne_mouvement.DateDebut<='".$DateJour."'
				AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$DateJour."')
				AND rh_personne_mouvement.EtatValidation=1 
				AND rh_personne_mouvement.Id_Personne=".$Id_Personne."
				AND rh_personne_mouvement.Id_Prestation<>0
			)
		)
		";
	$result=mysqli_query($bdd,$req);
	$nb=mysqli_num_rows($result);
	if($nb>0){
		$rowJour=mysqli_fetch_array($result);
		$EstFixe=$rowJour['CodePlanning'];
	}
	return $EstFixe;
}

//Renvoi 1 si c'est un jour férier pour cette plateforme
function estJourFerie($DateJour,$Id_Plateforme,$Id_Prestation=0){
	global $bdd;
	$EstFerie = 0;
	
	$req="SELECT rh_jourfixe.Id 
		FROM rh_jourfixe 
		WHERE DateJour='".$DateJour."' 
		AND rh_jourfixe.Suppr=0 
		AND Id_Plateforme=".$Id_Plateforme." 
		AND (Id_Prestation=0 OR Id_Prestation=".$Id_Prestation.")
		AND Id_TypeAbsence=10
		";

	
	$result=mysqli_query($bdd,$req);
	$nb=mysqli_num_rows($result);
	if($nb>0){
		$EstFerie=1;
	}
	return $EstFerie;
}

//Renvoi 1 si c'est calendaire
function estCalendaire($Id_Type){
	global $bdd;
	$EstCalendaire = 0;
	
	$req="SELECT rh_typeabsence.Id 
		FROM rh_typeabsence 
		WHERE Id=".$Id_Type." 
		AND JourCalendaire=1
		";
	$result=mysqli_query($bdd,$req);
	$nb=mysqli_num_rows($result);
	if($nb>0){
		$EstCalendaire=1;
	}
	return $EstCalendaire;
}

//Renvoi le code planning si c'est un jour fixe 
function estJour_Fixe_Id($DateJour,$Id_Personne){
	global $bdd;
	$EstFixe = "";
	
	$req="SELECT rh_jourfixe.Id_TypeAbsence AS Id, CodePlanning 
		FROM rh_jourfixe 
		LEFT JOIN rh_typeabsence
		ON rh_jourfixe.Id_TypeAbsence=rh_typeabsence.Id
		WHERE DateJour='".$DateJour."' 
		AND rh_jourfixe.Suppr=0 
		AND Id_Plateforme IN (
			SELECT Id_Plateforme 
			FROM rh_personne_mouvement
			LEFT JOIN new_competences_prestation
			ON rh_personne_mouvement.Id_Prestation=new_competences_prestation.Id
			WHERE rh_personne_mouvement.DateDebut<='".$DateJour."'
			AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$DateJour."')
			AND rh_personne_mouvement.EtatValidation=1 
			AND rh_personne_mouvement.Id_Personne=".$Id_Personne."
		) 
		AND (Id_Prestation=0 OR Id_Prestation IN (
				SELECT Id_Prestation 
				FROM rh_personne_mouvement
				LEFT JOIN new_competences_prestation
				ON rh_personne_mouvement.Id_Prestation=new_competences_prestation.Id
				WHERE rh_personne_mouvement.DateDebut<='".$DateJour."'
				AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$DateJour."')
				AND rh_personne_mouvement.EtatValidation=1 
				AND rh_personne_mouvement.Id_Personne=".$Id_Personne."
				AND rh_personne_mouvement.Id_Prestation<>0
			)
		)
		";
	$result=mysqli_query($bdd,$req);
	$nb=mysqli_num_rows($result);
	if($nb>0){
		$rowJour=mysqli_fetch_array($result);
		$EstFixe=$rowJour['Id'];
	}
	return $EstFixe;
}


//Renvoi le code couleur si la personne travaille un lundi à une date donnée sinon renvoi 0
function TravailCeJourDeSemaine($DateJour,$Id_Personne){
	global $bdd;
	$travail="";
	
	$tabDate = explode('-', $DateJour);
	$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
	$jourSemaine = date('w', $timestamp);
	$Semaine = date('W', $timestamp);
	
	//Couleur du J 
	$req="SELECT Couleur FROM rh_vacation WHERE Id=1";
	$result=mysqli_query($bdd,$req);
	$rowJ=mysqli_fetch_array($result);
	
	$reqContrat="SELECT Id,Id_Personne,DateDebut,DateFin,Id_TempsTravail
				FROM rh_personne_contrat
				WHERE Suppr=0
				AND DateDebut<='".$DateJour."'
				AND (DateFin>='".$DateJour."' OR DateFin<='0001-01-01' )
				AND Id_Personne=".$Id_Personne."
				AND TypeDocument IN ('Nouveau','Avenant')
				ORDER BY DateDebut DESC, Id DESC
				";
	$result=mysqli_query($bdd,$reqContrat);
	$nb=mysqli_num_rows($result);
	if($nb>0){	
		$rowContrat=mysqli_fetch_array($result);
		
		
		//Verifier si temps partiel
		$reqTP="SELECT Id
				FROM rh_personne_contrat_tempspartiel
				WHERE Suppr=0
				AND Id_Vacation=1
				AND (NbHeureJour+NbHeureEJ+NbHeureEN+NbHeurePause)>0
				AND Id_Personne_Contrat=".$rowContrat['Id']."
				";
		$resultTP=mysqli_query($bdd,$reqTP);
		$nbTP=mysqli_num_rows($resultTP);		
		
		if($nbTP>0){
			//Vacation = 1 (J) et Nb heure de jour >0
			if ($Semaine%2 == 1){
				$reqTP="SELECT Id
						FROM rh_personne_contrat_tempspartiel
						WHERE Suppr=0
						AND Id_Personne_Contrat=".$rowContrat['Id']."
						AND Id_Vacation=11
						AND (NbHeureJour+NbHeureEJ+NbHeureEN+NbHeurePause)>0
						";
				$resultTP=mysqli_query($bdd,$reqTP);
				$nbTPJour2=mysqli_num_rows($resultTP);
				if($nbTPJour2>0){
					$reqTP="SELECT Id
							FROM rh_personne_contrat_tempspartiel
							WHERE Suppr=0
							AND Id_Personne_Contrat=".$rowContrat['Id']."
							AND Id_Vacation=11
							AND (NbHeureJour+NbHeureEJ+NbHeureEN+NbHeurePause)>0
							AND JourSemaine=".$jourSemaine."
							";
					$resultTP=mysqli_query($bdd,$reqTP);
					$nbTPJour=mysqli_num_rows($resultTP);
				}else{
					$reqTP="SELECT Id
							FROM rh_personne_contrat_tempspartiel
							WHERE Suppr=0
							AND Id_Personne_Contrat=".$rowContrat['Id']."
							AND Id_Vacation=1
							AND (NbHeureJour+NbHeureEJ+NbHeureEN+NbHeurePause)>0
							AND JourSemaine=".$jourSemaine."
							";
					$resultTP=mysqli_query($bdd,$reqTP);
					$nbTPJour=mysqli_num_rows($resultTP);
				}
			}
			else{
				$reqTP="SELECT Id
						FROM rh_personne_contrat_tempspartiel
						WHERE Suppr=0
						AND Id_Personne_Contrat=".$rowContrat['Id']."
						AND Id_Vacation=1
						AND (NbHeureJour+NbHeureEJ+NbHeureEN+NbHeurePause)>0
						AND JourSemaine=".$jourSemaine."
						";
				$resultTP=mysqli_query($bdd,$reqTP);
				$nbTPJour=mysqli_num_rows($resultTP);
			}
			if($nbTPJour>0){$travail=$rowJ['Couleur'];}
			
			//Si VSD
			if($rowContrat['Id_TempsTravail']==18 || $rowContrat['Id_TempsTravail']==41){

			}
			else{
				//Si on est un lundi et que le jour précédent est en VSD alors ne travail pas 
				if($jourSemaine==1){
					$tabDate = explode('-', $DateJour);
					$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]-1, $tabDate[0]);
					$dateVeille = date("Y-m-d", $timestamp);
					$reqContratVeille="SELECT Id,Id_Personne,DateDebut,DateFin,Id_TempsTravail
								FROM rh_personne_contrat
								WHERE Suppr=0
								AND DateDebut<='".$dateVeille."'
								AND (DateFin>='".$dateVeille."' OR DateFin<='0001-01-01' )
								AND Id_Personne=".$Id_Personne."
								AND TypeDocument IN ('Nouveau','Avenant')
								ORDER BY DateDebut DESC, Id DESC
								";
					
					$resultVeille=mysqli_query($bdd,$reqContratVeille);
					$nbVeille=mysqli_num_rows($resultVeille);
					if($nbVeille>0){	
						$rowContratVeille=mysqli_fetch_array($resultVeille);
						if($rowContratVeille['Id_TempsTravail']==18 || $rowContratVeille['Id_TempsTravail']==41){
							$travail="";
						}
					}
				}
			}
		}
		else{
			//Si VSD
			if($rowContrat['Id_TempsTravail']==18){
				if($jourSemaine==0 || $jourSemaine==6 || $jourSemaine==5){$travail=$rowJ['Couleur'];}
				else{$travail="";}
			}
			elseif($rowContrat['Id_TempsTravail']==41){
				if($jourSemaine==0 || $jourSemaine==6){$travail=$rowJ['Couleur'];}
				else{$travail="";}
			}
			elseif($rowContrat['Id_TempsTravail']==29){
				if($jourSemaine==0 || $jourSemaine==6 || $jourSemaine==1){$travail=$rowJ['Couleur'];}
				else{$travail="";}
			}
			else{
				
				if($jourSemaine==0 || $jourSemaine==6){$travail="";}
				else{$travail=$rowJ['Couleur'];}
				
				//Si on est un lundi et que le jour précédent est en VSD alors ne travail pas 
				if($jourSemaine==1){
					$tabDate = explode('-', $DateJour);
					$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]-1, $tabDate[0]);
					$dateVeille = date("Y-m-d", $timestamp);
					$reqContratVeille="SELECT Id,Id_Personne,DateDebut,DateFin,Id_TempsTravail
								FROM rh_personne_contrat
								WHERE Suppr=0
								AND DateDebut<='".$dateVeille."'
								AND (DateFin>='".$dateVeille."' OR DateFin<='0001-01-01' )
								AND Id_Personne=".$Id_Personne."
								AND TypeDocument IN ('Nouveau','Avenant')
								ORDER BY DateDebut DESC, Id DESC
								";
					
					$resultVeille=mysqli_query($bdd,$reqContratVeille);
					$nbVeille=mysqli_num_rows($resultVeille);
					if($nbVeille>0){	
						$rowContratVeille=mysqli_fetch_array($resultVeille);
						if($rowContratVeille['Id_TempsTravail']==18 || $rowContratVeille['Id_TempsTravail']==41){
							$travail="";
						}
					}
				}
			}
		}
	}
	return $travail;
}

//Renvoi le code couleur si la personne travaille un lundi à une date donnée sinon renvoi 0
//Uniquement pour les demandes de congés, si jamais la personne n'a pas encore son prochain contrat de fait
function TravailCeJourDeSemaineDernierContrat($DateJour,$Id_Personne){
	global $bdd;
	$travail="";
	
	$tabDate = explode('-', $DateJour);
	$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
	$jourSemaine = date('w', $timestamp);
	
	//Couleur du J 
	$req="SELECT Couleur FROM rh_vacation WHERE Id=1";
	$result=mysqli_query($bdd,$req);
	$rowJ=mysqli_fetch_array($result);
	
	$reqContrat="SELECT Id,Id_Personne,DateDebut,DateFin,Id_TempsTravail
				FROM rh_personne_contrat
				WHERE Suppr=0
				AND Id_Personne=".$Id_Personne."
				AND TypeDocument IN ('Nouveau','Avenant')
				ORDER BY DateDebut DESC, Id DESC
				";
	$result=mysqli_query($bdd,$reqContrat);
	$nb=mysqli_num_rows($result);
	if($nb>0){	
		$rowContrat=mysqli_fetch_array($result);
		
		//Verifier si temps partiel
		$reqTP="SELECT Id
				FROM rh_personne_contrat_tempspartiel
				WHERE Suppr=0
				AND Id_Vacation=1
				AND (NbHeureJour+NbHeureEJ+NbHeureEN+NbHeurePause)>0
				AND Id_Personne_Contrat=".$rowContrat['Id']."
				";
		$resultTP=mysqli_query($bdd,$reqTP);
		$nbTP=mysqli_num_rows($resultTP);		
		if($nbTP>0){
			//Vacation = 1 (J) et Nb heure de jour >0
			$reqTP="SELECT Id
					FROM rh_personne_contrat_tempspartiel
					WHERE Suppr=0
					AND Id_Personne_Contrat=".$rowContrat['Id']."
					AND Id_Vacation=1
					AND (NbHeureJour+NbHeureEJ+NbHeureEN+NbHeurePause)>0
					AND JourSemaine=".$jourSemaine."
					";
			$resultTP=mysqli_query($bdd,$reqTP);
			$nbTPJour=mysqli_num_rows($resultTP);
			if($nbTPJour>0){$travail=$rowJ['Couleur'];}
		}
		else{
			//Si VSD
			if($rowContrat['Id_TempsTravail']==18){
				if($jourSemaine==0 || $jourSemaine==6 || $jourSemaine==5){$travail=$rowJ['Couleur'];}
				else{$travail="";}
			}
			elseif($rowContrat['Id_TempsTravail']==41){
				if($jourSemaine==0 || $jourSemaine==6){$travail=$rowJ['Couleur'];}
				else{$travail="";}
			}
			elseif($rowContrat['Id_TempsTravail']==29){
				if($jourSemaine==0 || $jourSemaine==6 || $jourSemaine==1){$travail=$rowJ['Couleur'];}
				else{$travail="";}
			}
			else{
				if($jourSemaine==0 || $jourSemaine==6){$travail="";}
				else{$travail=$rowJ['Couleur'];}
			}
		}
	}
	return $travail;
}

//Renvoi le code couleur si la personne travaille un lundi à une date donnée sinon renvoi 0
//Uniquement pour les demandes de congés, si jamais la personne n'a pas encore son prochain contrat de fait
function Id_TypeContratDernierContrat($DateJour,$Id_Personne){
	global $bdd;
	$travail="";
	
	$tabDate = explode('-', $DateJour);
	$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
	$jourSemaine = date('w', $timestamp);
	
	//Couleur du J 
	$req="SELECT Couleur FROM rh_vacation WHERE Id=1";
	$result=mysqli_query($bdd,$req);
	$rowJ=mysqli_fetch_array($result);
	
	$reqContrat="SELECT Id,Id_Personne,DateDebut,DateFin,Id_TempsTravail
				FROM rh_personne_contrat
				WHERE Suppr=0
				AND Id_Personne=".$Id_Personne."
				AND TypeDocument IN ('Nouveau','Avenant')
				ORDER BY DateDebut DESC, Id DESC
				";
	$result=mysqli_query($bdd,$reqContrat);
	$nb=mysqli_num_rows($result);
	if($nb>0){	
		$rowContrat=mysqli_fetch_array($result);
		$travail=$rowContrat['Id_TempsTravail'];
	}
	return $travail;
}

//Renvoi l'Id Vacation si la personne travaille à une date donnée sinon renvoi 0
function IdVacationCeJourDeSemaine($DateJour,$Id_Personne){
	global $bdd;
	$travail="";
	
	$tabDate = explode('-', $DateJour);
	$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
	$jourSemaine = date('w', $timestamp);
	$Semaine = date('W', $timestamp);
	
	$reqContrat="SELECT Id,Id_Personne,DateDebut,DateFin,Id_TempsTravail
				FROM rh_personne_contrat
				WHERE Suppr=0
				AND DateDebut<='".$DateJour."'
				AND (DateFin>='".$DateJour."' OR DateFin<='0001-01-01' )
				AND Id_Personne=".$Id_Personne."
				AND TypeDocument IN ('Nouveau','Avenant')
				ORDER BY DateDebut DESC, Id DESC
				";
	$result=mysqli_query($bdd,$reqContrat);
	$nb=mysqli_num_rows($result);
	if($nb>0){	
		$rowContrat=mysqli_fetch_array($result);
		
		//Verifier si temps partiel
		$reqTP="SELECT Id
				FROM rh_personne_contrat_tempspartiel
				WHERE Suppr=0
				AND Id_Vacation=1
				AND (NbHeureJour+NbHeureEJ+NbHeureEN+NbHeurePause)>0
				AND Id_Personne_Contrat=".$rowContrat['Id']."
				";
		$resultTP=mysqli_query($bdd,$reqTP);
		$nbTP=mysqli_num_rows($resultTP);		
		if($nbTP>0){
			//Vacation = 1 (J) et Nb heure de jour >0
			if ($Semaine%2 == 1){
				$reqTP="SELECT Id
						FROM rh_personne_contrat_tempspartiel
						WHERE Suppr=0
						AND Id_Personne_Contrat=".$rowContrat['Id']."
						AND Id_Vacation=11
						AND (NbHeureJour+NbHeureEJ+NbHeureEN+NbHeurePause)>0
						";
				$resultTP=mysqli_query($bdd,$reqTP);
				$nbTPJour2=mysqli_num_rows($resultTP);
				if($nbTPJour2>0){
					$reqTP="SELECT Id
							FROM rh_personne_contrat_tempspartiel
							WHERE Suppr=0
							AND Id_Personne_Contrat=".$rowContrat['Id']."
							AND Id_Vacation=11
							AND (NbHeureJour+NbHeureEJ+NbHeureEN+NbHeurePause)>0
							AND JourSemaine=".$jourSemaine."
							";
					$resultTP=mysqli_query($bdd,$reqTP);
					$nbTPJour=mysqli_num_rows($resultTP);
				}else{
					$reqTP="SELECT Id
							FROM rh_personne_contrat_tempspartiel
							WHERE Suppr=0
							AND Id_Personne_Contrat=".$rowContrat['Id']."
							AND Id_Vacation=1
							AND (NbHeureJour+NbHeureEJ+NbHeureEN+NbHeurePause)>0
							AND JourSemaine=".$jourSemaine."
							";
					$resultTP=mysqli_query($bdd,$reqTP);
					$nbTPJour=mysqli_num_rows($resultTP);
				}
			}
			else{
				$reqTP="SELECT Id
						FROM rh_personne_contrat_tempspartiel
						WHERE Suppr=0
						AND Id_Personne_Contrat=".$rowContrat['Id']."
						AND Id_Vacation=1
						AND (NbHeureJour+NbHeureEJ+NbHeureEN+NbHeurePause)>0
						AND JourSemaine=".$jourSemaine."
						";
				$resultTP=mysqli_query($bdd,$reqTP);
				$nbTPJour=mysqli_num_rows($resultTP);
			}
			if($nbTPJour>0){$travail=1;}
		}
		else{
			//Si VSD
			if($rowContrat['Id_TempsTravail']==18){
				if($jourSemaine==0 || $jourSemaine==6 || $jourSemaine==5){$travail=3;}
				else{$travail=0;}
			}
			elseif($rowContrat['Id_TempsTravail']==41){
				if($jourSemaine==0 || $jourSemaine==6){$travail=18;}
				else{$travail=0;}
			}
			elseif($rowContrat['Id_TempsTravail']==29){
				if($jourSemaine==0 || $jourSemaine==6 || $jourSemaine==1){$travail=15;}
				else{$travail=0;}
			}
			else{
				if($jourSemaine==0 || $jourSemaine==6){$travail=0;}
				else{$travail=1;}
			}
		}
	}
	return $travail;
}

//Renvoi l'Id Vacation si la personne travaille à une date donnée sinon renvoi 0
function IdVacationCeJourDeSemaineDernierContrat($DateJour,$Id_Personne){
	global $bdd;
	$travail="";
	
	$tabDate = explode('-', $DateJour);
	$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
	$jourSemaine = date('w', $timestamp);
	
	$reqContrat="SELECT Id,Id_Personne,DateDebut,DateFin,Id_TempsTravail
				FROM rh_personne_contrat
				WHERE Suppr=0
				AND Id_Personne=".$Id_Personne."
				AND TypeDocument IN ('Nouveau','Avenant')
				ORDER BY DateDebut DESC, Id DESC
				";
	$result=mysqli_query($bdd,$reqContrat);
	$nb=mysqli_num_rows($result);
	if($nb>0){	
		$rowContrat=mysqli_fetch_array($result);
		
		//Verifier si temps partiel
		$reqTP="SELECT Id
				FROM rh_personne_contrat_tempspartiel
				WHERE Suppr=0
				AND Id_Vacation=1
				AND (NbHeureJour+NbHeureEJ+NbHeureEN+NbHeurePause)>0
				AND Id_Personne_Contrat=".$rowContrat['Id']."
				";
		$resultTP=mysqli_query($bdd,$reqTP);
		$nbTP=mysqli_num_rows($resultTP);		
		if($nbTP>0){
			//Vacation = 1 (J) et Nb heure de jour >0
			$reqTP="SELECT Id
					FROM rh_personne_contrat_tempspartiel
					WHERE Suppr=0
					AND Id_Personne_Contrat=".$rowContrat['Id']."
					AND Id_Vacation=1
					AND (NbHeureJour+NbHeureEJ+NbHeureEN+NbHeurePause)>0
					AND JourSemaine=".$jourSemaine."
					";
			$resultTP=mysqli_query($bdd,$reqTP);
			$nbTPJour=mysqli_num_rows($resultTP);
			if($nbTPJour>0){$travail=1;}
		}
		else{
			//Si VSD
			if($rowContrat['Id_TempsTravail']==18){
				if($jourSemaine==0 || $jourSemaine==6 || $jourSemaine==5){$travail=3;}
				else{$travail=0;}
			}
			elseif($rowContrat['Id_TempsTravail']==41){
				if($jourSemaine==0 || $jourSemaine==6 ){$travail=18;}
				else{$travail=0;}
			}
			elseif($rowContrat['Id_TempsTravail']==29){
				if($jourSemaine==0 || $jourSemaine==6 || $jourSemaine==1){$travail=15;}
				else{$travail=0;}
			}
			else{
				if($jourSemaine==0 || $jourSemaine==6){$travail=0;}
				else{$travail=1;}
			}
		}
	}
	return $travail;
}

//Renvoi un Id_Vacation si la personne a une vacation particulière ce jour là
function VacationPersonne($DateJour,$Id_Personne,$Id_Prestation,$Id_Pole){
	global $bdd;
	$Id_Vacation=0;
	
	$req="SELECT Id_Vacation 
		FROM rh_personne_vacation
		WHERE Suppr=0
		AND Id_Personne=".$Id_Personne." 
		AND Id_Prestation=".$Id_Prestation." 
		AND Id_Pole=".$Id_Pole." 
		AND DateVacation='".$DateJour."' ";
	$result=mysqli_query($bdd,$req);
	$nb=mysqli_num_rows($result);	
	if($nb>0){
		$rowVac=mysqli_fetch_array($result);
		$Id_Vacation=$rowVac['Id_Vacation'];
	}
	return $Id_Vacation;
}

//Renvoi si c'est une vacation mise par les RH
function VacationPersonneEmisParRH($DateJour,$Id_Personne,$Id_Prestation,$Id_Pole){
	global $bdd;
	$EmisParRH=0;
	
	$req="SELECT EmisParRH 
		FROM rh_personne_vacation
		WHERE Suppr=0
		AND Id_Personne=".$Id_Personne." 
		AND Id_Prestation=".$Id_Prestation." 
		AND Id_Pole=".$Id_Pole." 
		AND DateVacation='".$DateJour."' ";
	$result=mysqli_query($bdd,$req);
	$nb=mysqli_num_rows($result);	
	if($nb>0){
		$rowVac=mysqli_fetch_array($result);
		$EmisParRH=$rowVac['EmisParRH'];
	}
	return $EmisParRH;
}

//Renvoi la valeur de la case divers
function VacationPersonneDivers($DateJour,$Id_Personne,$Id_Prestation,$Id_Pole){
	global $bdd;
	$Divers="";
	
	$req="SELECT Divers 
		FROM rh_personne_vacation
		WHERE Suppr=0
		AND Id_Personne=".$Id_Personne." 
		AND Id_Prestation=".$Id_Prestation." 
		AND Id_Pole=".$Id_Pole." 
		AND DateVacation='".$DateJour."' ";
	$result=mysqli_query($bdd,$req);
	$nb=mysqli_num_rows($result);	
	if($nb>0){
		$rowVac=mysqli_fetch_array($result);
		$Divers=$rowVac['Divers'];
	}
	return $Divers;
}

//Renvoi la valeur de la case commentaire
function VacationPersonneCommentaire($DateJour,$Id_Personne,$Id_Prestation,$Id_Pole){
	global $bdd;
	$Commentaire="";
	
	$req="SELECT Commentaire 
		FROM rh_personne_vacation
		WHERE Suppr=0
		AND Id_Personne=".$Id_Personne." 
		AND Id_Prestation=".$Id_Prestation." 
		AND Id_Pole=".$Id_Pole." 
		AND DateVacation='".$DateJour."' ";
	$result=mysqli_query($bdd,$req);
	$nb=mysqli_num_rows($result);	
	if($nb>0){
		$rowVac=mysqli_fetch_array($result);
		$Commentaire=$rowVac['Commentaire'];
	}
	return $Commentaire;
}

//Renvoi l'Id_Prestation + l'Id_Pole de la personne à une date de donnée
function PrestationPole_Personne($DateJour,$Id_Personne){
	global $bdd;
	$Id_PrestationPole=0;
	
	$req="SELECT Id_Prestation,Id_Pole 
		FROM rh_personne_mouvement
		WHERE Suppr=0
		AND Id_Personne=".$Id_Personne." 
		AND EtatValidation=1
		AND rh_personne_mouvement.DateDebut<='".$DateJour."'
		AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$DateJour."') ";

	$result=mysqli_query($bdd,$req);
	$nb=mysqli_num_rows($result);	
	if($nb>0){
		$rowMouv=mysqli_fetch_array($result);
		$Id_PrestationPole=$rowMouv['Id_Prestation']."_".$rowMouv['Id_Pole'];
	}
	return $Id_PrestationPole;
}

//Renvoi l'Id_Prestation + l'Id_Pole de la personne à une date de donnée
function PrestationPoleCompetence_Personne($DateJour,$Id_Personne){
	global $bdd;
	$Id_PrestationPole=0;
	
	$req="SELECT Id_Prestation,Id_Pole 
		FROM new_competences_personne_prestation
		WHERE Id_Personne=".$Id_Personne." 
		AND new_competences_personne_prestation.Date_Debut<='".$DateJour."'
		AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".$DateJour."') ";

	$result=mysqli_query($bdd,$req);
	$nb=mysqli_num_rows($result);	
	if($nb>0){
		$rowMouv=mysqli_fetch_array($result);
		$Id_PrestationPole=$rowMouv['Id_Prestation']."_".$rowMouv['Id_Pole'];
	}
	return $Id_PrestationPole;
}

//Renvoi l'Id_Prestation + l'Id_Pole de la personne à une date de donnée
function Prestation_Personne($DateJour,$Id_Personne){
	global $bdd;
	$Id_Prestation=0;
	
	$req="SELECT Id_Prestation,Id_Pole 
		FROM rh_personne_mouvement
		WHERE Suppr=0
		AND Id_Personne=".$Id_Personne." 
		AND EtatValidation=1
		AND rh_personne_mouvement.DateDebut<='".$DateJour."'
		AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$DateJour."') ";

	$result=mysqli_query($bdd,$req);
	$nb=mysqli_num_rows($result);	
	if($nb>0){
		$rowMouv=mysqli_fetch_array($result);
		$Id_Prestation=$rowMouv['Id_Prestation'];
	}
	return $Id_Prestation;
}

//Renvoi l'Id_Prestation + l'Id_Pole de la personne à une date de donnée
function PrestationPoleLibelle_Personne($DateJour,$Id_Personne){
	global $bdd;
	$Id_PrestationPole="";
	
	$req="SELECT (SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
		(SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole) AS Pole
		FROM rh_personne_mouvement
		WHERE Suppr=0
		AND Id_Personne=".$Id_Personne." 
		AND EtatValidation=1
		AND rh_personne_mouvement.DateDebut<='".$DateJour."'
		AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$DateJour."') ";

	$result=mysqli_query($bdd,$req);
	$nb=mysqli_num_rows($result);	
	if($nb>0){
		$rowMouv=mysqli_fetch_array($result);
		$Id_PrestationPole=$rowMouv['Prestation']." ".$rowMouv['Pole'];
	}
	return $Id_PrestationPole;
}

//Renvoi la liste des prestations et pole de la personne pour une période donnée
function PrestationsPoleLibelle_Personne($DateJour,$DateFin,$Id_Personne){
	global $bdd;
	$PrestationPole="";
	
	$req="SELECT DISTINCT (SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
		(SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole) AS Pole
		FROM rh_personne_mouvement
		WHERE Suppr=0
		AND Id_Personne=".$Id_Personne." 
		AND EtatValidation=1
		AND rh_personne_mouvement.DateDebut<='".$DateFin."'
		AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$DateJour."') ";

	$result=mysqli_query($bdd,$req);
	$nb=mysqli_num_rows($result);	
	if($nb>0){
		$rowMouv=mysqli_fetch_array($result);
		if($PrestationPole<>""){$PrestationPole.="<br>";}
		$PrestationPole.=$rowMouv['Prestation']." ".$rowMouv['Pole'];
	}
	return $PrestationPole;
}

//Vérifie si la personne est salarié à un jour donnée 
function estSalarie($DateJour,$Id_Personne){
	global $bdd;
	$estUnSalarie=0;
	
	$tabDate = explode('-', $DateJour);
	$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
	$jourSemaine = date('w', $timestamp);
	
	$reqContrat="SELECT Id,Id_Personne,DateDebut,DateFin,Id_TempsTravail,
				(SELECT EstSalarie FROM rh_typecontrat WHERE rh_typecontrat.Id=rh_personne_contrat.Id_TypeContrat) AS EstSalarie
				FROM rh_personne_contrat
				WHERE Suppr=0
				AND DateDebut<='".$DateJour."'
				AND (DateFin>='".$DateJour."' OR DateFin<='0001-01-01' )
				AND Id_Personne=".$Id_Personne."
				AND TypeDocument IN ('Nouveau','Avenant')
				ORDER BY DateDebut DESC, Id DESC
				LIMIT 1
				";
	$result=mysqli_query($bdd,$reqContrat);
	$nb=mysqli_num_rows($result);
	
	if($nb>0){
		$rowContrat=mysqli_fetch_array($result);
		$estUnSalarie=$rowContrat['EstSalarie'];
	}
	return $estUnSalarie;
}

//Vérifie si la personne est salarié à un jour donnée 
function estInterne($DateJour,$Id_Personne){
	global $bdd;
	$estUnSalarie=0;
	
	$tabDate = explode('-', $DateJour);
	$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
	$jourSemaine = date('w', $timestamp);
	
	$reqContrat="SELECT Id,Id_Personne,DateDebut,DateFin,Id_TempsTravail,
				(SELECT EstInterne FROM rh_typecontrat WHERE rh_typecontrat.Id=rh_personne_contrat.Id_TypeContrat) AS EstInterne
				FROM rh_personne_contrat
				WHERE Suppr=0
				AND DateDebut<='".$DateJour."'
				AND (DateFin>='".$DateJour."' OR DateFin<='0001-01-01' )
				AND Id_Personne=".$Id_Personne."
				AND TypeDocument IN ('Nouveau','Avenant')
				ORDER BY DateDebut DESC, Id DESC
				";
	$result=mysqli_query($bdd,$reqContrat);
	$nb=mysqli_num_rows($result);
	
	if($nb>0){
		$rowContrat=mysqli_fetch_array($result);
		$estUnSalarie=$rowContrat['EstInterne'];
	}
	return $estUnSalarie;
}

//Vérifie si la personne est salarié à un jour donnée 
function estInterim($DateJour,$Id_Personne){
	global $bdd;
	$estUnInterim=0;
	
	$tabDate = explode('-', $DateJour);
	$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
	$jourSemaine = date('w', $timestamp);
	
	$reqContrat="SELECT Id,Id_Personne,DateDebut,DateFin,Id_TempsTravail,
				(SELECT EstInterim FROM rh_typecontrat WHERE rh_typecontrat.Id=rh_personne_contrat.Id_TypeContrat) AS EstInterim
				FROM rh_personne_contrat
				WHERE Suppr=0
				AND DateDebut<='".$DateJour."'
				AND (DateFin>='".$DateJour."' OR DateFin<='0001-01-01' )
				AND Id_Personne=".$Id_Personne."
				AND TypeDocument IN ('Nouveau','Avenant')
				ORDER BY DateDebut DESC, Id DESC
				";
	$result=mysqli_query($bdd,$reqContrat);
	$nb=mysqli_num_rows($result);
	
	if($nb>0){
		$rowContrat=mysqli_fetch_array($result);
		$estUnInterim=$rowContrat['EstInterim'];
	}
	return $estUnInterim;
}

//Vérifie si la personne est salarié à un jour donnée 
function estInterimPourMenu($DateJour,$Id_Personne){
	global $bdd;
	$estUnInterim=0;
	
	$tabDate = explode('-', $DateJour);
	$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
	$jourSemaine = date('w', $timestamp);
	
	$reqContrat="SELECT Id,Id_Personne,DateDebut,DateFin,Id_TempsTravail,
				(SELECT EstInterim FROM rh_typecontrat WHERE rh_typecontrat.Id=rh_personne_contrat.Id_TypeContrat) AS EstInterim
				FROM rh_personne_contrat
				WHERE Suppr=0
				AND DateDebut<='".$DateJour."'
				AND (DateFin>='".$DateJour."' OR DateFin<='0001-01-01' )
				AND Id_Personne=".$Id_Personne."
				AND TypeDocument IN ('Nouveau','Avenant')
				ORDER BY DateDebut DESC, Id DESC
				";
	$result=mysqli_query($bdd,$reqContrat);
	$nb=mysqli_num_rows($result);

	if($nb>0){
		$rowContrat=mysqli_fetch_array($result);
		$estUnInterim=$rowContrat['EstInterim'];
	}
	else{
		$req="SELECT Contrat FROM new_rh_etatcivil WHERE Id=".$Id_Personne;
		$resultEtatCivil=mysqli_query($bdd,$req);
		$nbEtatCivil=mysqli_num_rows($resultEtatCivil);
		if($nbEtatCivil>0){
			$rowEtatCivil=mysqli_fetch_array($resultEtatCivil);
			if($rowEtatCivil['Contrat']=='Intérimaire' || $rowEtatCivil['Contrat']=='Sous-traitant'){$estUnInterim=1;}
		}
	}
	return $estUnInterim;
}

//Vérifie si la personne est sous-traitant
function estSousTraitantPourMenu($Id_Personne){
	global $bdd;
	$estUnInterim=0;
	
	$req="SELECT Contrat FROM new_rh_etatcivil WHERE Id=".$Id_Personne;
	$resultEtatCivil=mysqli_query($bdd,$req);
	$nbEtatCivil=mysqli_num_rows($resultEtatCivil);
	if($nbEtatCivil>0){
		$rowEtatCivil=mysqli_fetch_array($resultEtatCivil);
		if($rowEtatCivil['Contrat']=='Sous-traitant'){$estUnInterim=1;}
	}
	return $estUnInterim;
}

//Renvoi le pointage d'une prestation pour une vacation pour un jour de la semaine
function PointagePrestationVacation($Id_Prestation,$Id_Pole,$Id_Vacation,$JourSemaine,$DateJour){
	global $bdd;
	$tab=array();
	$req="SELECT NbHeureJ,NbHeureEJ,NbHeureEN,NbHeurePause,NbHeureFOR  
		FROM rh_prestation_vacation
		WHERE Id_Prestation=".$Id_Prestation." 
		AND Id_Pole=".$Id_Pole."
		AND DateDebut<='".$DateJour."'
		AND (DateFin<='0001-01-01' OR DateFin>='".$DateJour."')
		AND Suppr=0
		AND Id_Vacation=".$Id_Vacation."
		AND JourSemaine=".$JourSemaine." ";

	$result=mysqli_query($bdd,$req);
	$nb=mysqli_num_rows($result);	
	if($nb>0){
		$rowVac=mysqli_fetch_array($result);
		$tab[0]=$rowVac['NbHeureJ'];
		$tab[1]=$rowVac['NbHeureEJ'];
		$tab[2]=$rowVac['NbHeureEN'];
		$tab[3]=$rowVac['NbHeurePause'];
		$tab[4]=$rowVac['NbHeureFOR'];
	}
	return $tab;
}

//Renvoi le pointage d'une prestation pour une vacation pour un jour de la semaine
function AfficheZeroPrestationVacation($Id_Prestation,$Id_Pole,$Id_Vacation,$JourSemaine,$DateJour){
	global $bdd;
	$zero=0;
	$req="SELECT AfficherZero  
		FROM rh_prestation_vacation
		WHERE Id_Prestation=".$Id_Prestation." 
		AND Id_Pole=".$Id_Pole."
		AND DateDebut<='".$DateJour."'
		AND (DateFin<='0001-01-01' OR DateFin>='".$DateJour."')
		AND Suppr=0
		AND Id_Vacation=".$Id_Vacation."
		AND JourSemaine=".$JourSemaine." ";

	$result=mysqli_query($bdd,$req);
	$nb=mysqli_num_rows($result);	
	if($nb>0){
		$rowVac=mysqli_fetch_array($result);
		$zero=$rowVac['AfficherZero'];
	}
	return $zero;
}

//Renvoi le pointage de la personne en fonction de son contrat
function PointagePersonneContrat($DateJour,$Id_Personne,$Id_Vacation,$JourSemaine){
	global $bdd;
	
	$tab=array();
	$reqContrat="SELECT Id,Id_Personne,DateDebut,DateFin,Id_TempsTravail,
				(SELECT EstSalarie FROM rh_typecontrat WHERE rh_typecontrat.Id=rh_personne_contrat.Id_TypeContrat) AS EstSalarie
				FROM rh_personne_contrat
				WHERE Suppr=0
				AND DateDebut<='".$DateJour."'
				AND (DateFin>='".$DateJour."' OR DateFin<='0001-01-01' )
				AND Id_Personne=".$Id_Personne."
				AND TypeDocument IN ('Nouveau','Avenant')
				ORDER BY DateDebut DESC, Id DESC
				";
	$result=mysqli_query($bdd,$reqContrat);
	$nb=mysqli_num_rows($result);	
	
	if($nb>0){
		$rowContrat=mysqli_fetch_array($result);
		if($rowContrat['Id_TempsTravail']==10 && $rowContrat['EstSalarie']==1 ){
			if($JourSemaine<>0 && $JourSemaine<>6){
				$tab[0]=1;
			}
			else{
				$tab[0]=0;
			}
			$tab[1]=0;
			$tab[2]=0;
			$tab[3]=0;
		}
		elseif($rowContrat['Id_TempsTravail']==5){
			//Verifier si temps partiel 
			$reqTP = "SELECT Id,NbHeureJour, NbHeureEJ, NbHeureEN, NbHeurePause,HeureDebut,HeureFin,Teletravail 
				FROM rh_personne_contrat_tempspartiel 
				WHERE Id_Personne_Contrat=".$rowContrat['Id']." 
				AND Suppr=0
				AND (NbHeureJour+NbHeureEJ+NbHeureEN+NbHeurePause)>0
				AND JourSemaine=".$JourSemaine." 
				AND Id_Vacation=".$Id_Vacation."";
			$resultTP=mysqli_query($bdd,$reqTP);
			$nbTP=mysqli_num_rows($resultTP);	
			if($nbTP>0){
				$rowTP=mysqli_fetch_array($resultTP);
				$tab[0]=$rowTP['NbHeureJour'];
				$tab[1]=$rowTP['NbHeureEJ'];
				$tab[2]=$rowTP['NbHeureEN'];
				$tab[3]=$rowTP['NbHeurePause'];
			}
		}
		else{
			//Verifier si temps partiel 
			$reqTP = "SELECT Id,NbHeureJour, NbHeureEJ, NbHeureEN, NbHeurePause,HeureDebut,HeureFin,Teletravail 
				FROM rh_personne_contrat_tempspartiel 
				WHERE Id_Personne_Contrat=".$rowContrat['Id']." 
				AND Suppr=0
				AND (NbHeureJour+NbHeureEJ+NbHeureEN+NbHeurePause)>0
				AND JourSemaine=".$JourSemaine." 
				AND Id_Vacation=".$Id_Vacation."";
			$resultTP=mysqli_query($bdd,$reqTP);
			$nbTP=mysqli_num_rows($resultTP);	
			if($nbTP>0){
				$rowTP=mysqli_fetch_array($resultTP);
				$tab[0]=$rowTP['NbHeureJour'];
				$tab[1]=$rowTP['NbHeureEJ'];
				$tab[2]=$rowTP['NbHeureEN'];
				$tab[3]=$rowTP['NbHeurePause'];
			}
		}
	}
	
	
	return $tab;
}

//Renvoi le pointage d'une prestation pour une vacation pour un jour de la semaine
function PointagePersonneExceptionnel($Id_Personne,$Id_Prestation,$Id_Pole,$DateJour){
	global $bdd;
	$tab=array();
	$req="SELECT NbHeureJour,NbHeureEquipeJour,NbHeureEquipeNuit,NbHeurePause,NbHeureFormation,NbHeureAPrendreEnCompte,NbHeureFormationETT
		FROM rh_personne_vacation
		WHERE Id_Prestation=".$Id_Prestation." 
		AND Id_Pole=".$Id_Pole."
		AND Id_Personne=".$Id_Personne."
		AND DateVacation='".$DateJour."'
		AND Suppr=0 ";
	$result=mysqli_query($bdd,$req);
	$nb=mysqli_num_rows($result);	
	if($nb>0){
		$rowVac=mysqli_fetch_array($result);
		$tab[0]=$rowVac['NbHeureJour'];
		$tab[1]=$rowVac['NbHeureEquipeJour'];
		$tab[2]=$rowVac['NbHeureEquipeNuit'];
		$tab[3]=$rowVac['NbHeurePause'];
		$tab[4]=$rowVac['NbHeureFormation'];
		$tab[5]=$rowVac['NbHeureAPrendreEnCompte'];
		$tab[6]=$rowVac['NbHeureFormationETT'];
	}
	return $tab;
}

//Renvoi le nombre de demandes de congées à valider par les RH ou le manager
function NombreDemandesCongesAValider($Id_Personne,$Manager){
	global $bdd;
	global $IdPosteResponsableRH;
	global $IdPosteAssistantRH;
	global $IdPosteAideRH;
	global $IdPosteResponsablePlateforme;
	global $IdPosteChefEquipe;
	global $IdPosteCoordinateurEquipe;
	global $IdPosteCoordinateurProjet;
	global $IdPosteResponsableProjet;
	
	$req="SELECT rh_personne_demandeabsence.Id
			FROM rh_personne_demandeabsence
			WHERE Suppr=0 AND Conge=1 AND ";
	if($Manager==1){
		$req.="(
				(CONCAT(rh_personne_demandeabsence.Id_Prestation,'_',rh_personne_demandeabsence.Id_Pole) IN 
					(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
					FROM new_competences_personne_poste_prestation 
					WHERE Id_Personne=".$Id_Personne."
					AND Id_Poste IN (".$IdPosteChefEquipe.") 
					AND (Backup=0 OR (SELECT ChefEquipeNonBackup FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_poste_prestation.Id_Prestation LIMIT 1)=1)
					)
				AND rh_personne_demandeabsence.EtatN1=0
				)
				OR 
				(CONCAT(rh_personne_demandeabsence.Id_Prestation,'_',rh_personne_demandeabsence.Id_Pole) IN 
					(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
					FROM new_competences_personne_poste_prestation 
					WHERE Id_Personne=".$Id_Personne."
					AND Id_Poste IN (".$IdPosteCoordinateurEquipe.") 
					AND Backup=0
					)
				AND rh_personne_demandeabsence.EtatN1=1
				AND rh_personne_demandeabsence.EtatN2=0
				)
				OR 
				(CONCAT(rh_personne_demandeabsence.Id_Prestation,'_',rh_personne_demandeabsence.Id_Pole) IN 
					(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
					FROM new_competences_personne_poste_prestation 
					WHERE Id_Personne=".$Id_Personne."
					AND Id_Poste IN (".$IdPosteCoordinateurProjet.") 
					AND Backup=0
					)
				AND rh_personne_demandeabsence.EtatN1=1
				AND rh_personne_demandeabsence.EtatN2=0
				AND (
						SELECT COUNT(rh_personne_mouvement.Id)
						FROM rh_personne_mouvement
						WHERE rh_personne_mouvement.Suppr=0
						AND rh_personne_mouvement.Id_Personne=rh_personne_demandeabsence.Id_Personne
						AND rh_personne_mouvement.Id_Prestation=rh_personne_demandeabsence.Id_Prestation
						AND rh_personne_mouvement.Id_Pole=rh_personne_demandeabsence.Id_Pole
						AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
						AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."')
						AND (SELECT COUNT(new_competences_personne_poste_prestation.Id) 
							FROM new_competences_personne_poste_prestation 
							WHERE new_competences_personne_poste_prestation.Id_Personne=rh_personne_mouvement.Id_Personne
							AND Id_Poste IN (".$IdPosteCoordinateurEquipe.") 
							AND rh_personne_mouvement.Id_Prestation=new_competences_personne_poste_prestation.Id_Prestation
							AND rh_personne_mouvement.Id_Pole=new_competences_personne_poste_prestation.Id_Pole
							)>0
					)>0
				)
			)
					";
	}
	else{
		$req.="(SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_demandeabsence.Id_Prestation) IN 
			(
				SELECT Id_Plateforme 
				FROM new_competences_personne_poste_plateforme
				WHERE Id_Personne=".$Id_Personne." 
				AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
			)
			AND rh_personne_demandeabsence.EtatN1<>-1
			AND rh_personne_demandeabsence.EtatN2=1
			AND rh_personne_demandeabsence.EtatRH=0
			";
		
	}
	$result=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($result);
	
	return $nbResulta;
}

//Renvoi le nombre de demandes de congées à valider par les RH ou le manager
function NombreDemandesCongesAValider48H($Id_Personne,$Manager){
	global $bdd;
	global $IdPosteResponsableRH;
	global $IdPosteAssistantRH;
	global $IdPosteAideRH;
	global $IdPosteResponsablePlateforme;
	global $IdPosteChefEquipe;
	global $IdPosteCoordinateurEquipe;
	global $IdPosteCoordinateurProjet;
	global $IdPosteResponsableProjet;
	
	$req="SELECT rh_personne_demandeabsence.Id
			FROM rh_personne_demandeabsence
			WHERE Suppr=0 AND Conge=1 AND 
			(SELECT COUNT(rh_absence.Id)
			FROM rh_absence
			WHERE rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
			AND rh_absence.Suppr=0
			AND rh_absence.DateDebut<='".date('Y-m-d',strtotime(date('Y-m-d')." + 2 day"))."'
			)>0
			AND 
			";
	if($Manager==1){
		$req.="(
				(CONCAT(rh_personne_demandeabsence.Id_Prestation,'_',rh_personne_demandeabsence.Id_Pole) IN 
					(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
					FROM new_competences_personne_poste_prestation 
					WHERE Id_Personne=".$Id_Personne."
					AND Id_Poste IN (".$IdPosteChefEquipe.") 
					AND (Backup=0 OR (SELECT ChefEquipeNonBackup FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_poste_prestation.Id_Prestation LIMIT 1)=1)
					)
				AND rh_personne_demandeabsence.EtatN1=0
				)
				OR 
				(CONCAT(rh_personne_demandeabsence.Id_Prestation,'_',rh_personne_demandeabsence.Id_Pole) IN 
					(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
					FROM new_competences_personne_poste_prestation 
					WHERE Id_Personne=".$Id_Personne."
					AND Id_Poste IN (".$IdPosteCoordinateurEquipe.") 
					AND Backup=0
					)
				AND rh_personne_demandeabsence.EtatN1=1
				AND rh_personne_demandeabsence.EtatN2=0
				)
				OR 
				(CONCAT(rh_personne_demandeabsence.Id_Prestation,'_',rh_personne_demandeabsence.Id_Pole) IN 
					(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
					FROM new_competences_personne_poste_prestation 
					WHERE Id_Personne=".$Id_Personne."
					AND Id_Poste IN (".$IdPosteCoordinateurProjet.") 
					AND Backup=0
					)
				AND rh_personne_demandeabsence.EtatN1=1
				AND rh_personne_demandeabsence.EtatN2=0
				AND (
						SELECT COUNT(rh_personne_mouvement.Id)
						FROM rh_personne_mouvement
						WHERE rh_personne_mouvement.Suppr=0
						AND rh_personne_mouvement.Id_Personne=rh_personne_demandeabsence.Id_Personne
						AND rh_personne_mouvement.Id_Prestation=rh_personne_demandeabsence.Id_Prestation
						AND rh_personne_mouvement.Id_Pole=rh_personne_demandeabsence.Id_Pole
						AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
						AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."')
						AND (SELECT CONCAT(new_competences_personne_poste_prestation.Id) 
							FROM new_competences_personne_poste_prestation 
							WHERE new_competences_personne_poste_prestation.Id_Personne=rh_personne_mouvement.Id_Personne
							AND Id_Poste IN (".$IdPosteCoordinateurEquipe.") 
							AND rh_personne_mouvement.Id_Prestation=new_competences_personne_poste_prestation.Id_Prestation
							AND rh_personne_mouvement.Id_Pole=new_competences_personne_poste_prestation.Id_Pole
							)>0
					)>0
				)
			)
					";
	}
	else{
		$req.="(SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_demandeabsence.Id_Prestation) IN 
			(
				SELECT Id_Plateforme 
				FROM new_competences_personne_poste_plateforme
				WHERE Id_Personne=".$Id_Personne." 
				AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
			)
			AND rh_personne_demandeabsence.EtatN2=1
			AND rh_personne_demandeabsence.EtatRH=0
			";
		
	}

	$result=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($result);
	
	return $nbResulta;
}

//Renvoi le nombre de demandes de congées E/C pour la personne connectée
function NombreDemandeCongesEC(){
	global $bdd;
	
	$req="SELECT rh_personne_demandeabsence.Id
			FROM rh_personne_demandeabsence
			WHERE Suppr=0 AND Conge=1 
			AND EtatN1<>-1
			AND EtatN2=0 
			AND Suppr=0
			AND Id_Personne=".$_SESSION['Id_Personne']."
			";
	$result=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($result);
	
	return $nbResulta;
}

//Renvoi le nombre de rapports d'astreintes à valider par les RH ou le manager
function NombreRapportsAstreinteAValider($Id_Personne,$Manager){
	global $bdd;
	global $IdPosteResponsableRH;
	global $IdPosteAssistantRH;
	global $IdPosteAideRH;
	global $IdPosteResponsablePlateforme;
	global $IdPosteChefEquipe;
	global $IdPosteCoordinateurEquipe;
	global $IdPosteCoordinateurProjet;
	global $IdPosteResponsableProjet;
	
	$req="SELECT rh_personne_rapportastreinte.Id
			FROM rh_personne_rapportastreinte
			WHERE Suppr=0 AND ";
	if($Manager==1){
		$req.="(
				(CONCAT(rh_personne_rapportastreinte.Id_Prestation,'_',rh_personne_rapportastreinte.Id_Pole) IN 
					(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
					FROM new_competences_personne_poste_prestation 
					WHERE Id_Personne=".$Id_Personne."
					AND Id_Poste IN (".$IdPosteChefEquipe.") 
					)
				AND rh_personne_rapportastreinte.EtatN1=0
				)
				OR 
				(CONCAT(rh_personne_rapportastreinte.Id_Prestation,'_',rh_personne_rapportastreinte.Id_Pole) IN 
					(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
					FROM new_competences_personne_poste_prestation 
					WHERE Id_Personne=".$Id_Personne."
					AND Id_Poste IN (".$IdPosteCoordinateurEquipe.") 
					)
				AND rh_personne_rapportastreinte.EtatN1=1
				AND rh_personne_rapportastreinte.EtatN2=0
				)
			)
					";
	}
	else{
		$req.="(SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_rapportastreinte.Id_Prestation) IN 
			(
				SELECT Id_Plateforme 
				FROM new_competences_personne_poste_plateforme
				WHERE Id_Personne=".$Id_Personne." 
				AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
			)
			AND rh_personne_rapportastreinte.EtatN2=1
			AND rh_personne_rapportastreinte.DateValidationRH<='0001-01-01'
			";
		
	}
	
	$result=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($result);
	
	return $nbResulta;
}

//Renvoi le nombre d'heures supplémentaires à valider par les RH ou le manager
function NombreHeuresSuppAValider($Id_Personne,$Manager){
	global $bdd;
	global $IdPosteResponsableRH;
	global $IdPosteAssistantRH;
	global $IdPosteAideRH;
	global $IdPosteResponsablePlateforme;
	global $IdPosteChefEquipe;
	global $IdPosteCoordinateurEquipe;
	global $IdPosteCoordinateurProjet;
	global $IdPosteResponsableProjet;
	
	$req="SELECT rh_personne_hs.Id
			FROM rh_personne_hs
			WHERE Suppr=0 AND ";
	if($Manager==1){
		$req.=" DatePriseEnCompteRH<='0001-01-01' AND (
				(CONCAT(rh_personne_hs.Id_Prestation,'_',rh_personne_hs.Id_Pole) IN 
					(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
					FROM new_competences_personne_poste_prestation 
					WHERE Id_Personne=".$Id_Personne."
					AND Id_Poste IN (".$IdPosteChefEquipe.") 
					)
				AND rh_personne_hs.Etat2=0
				AND rh_personne_hs.Etat3=0
				AND rh_personne_hs.Etat4=0
				)
				OR 
				(CONCAT(rh_personne_hs.Id_Prestation,'_',rh_personne_hs.Id_Pole) IN 
					(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
					FROM new_competences_personne_poste_prestation 
					WHERE Id_Personne=".$Id_Personne."
					AND Id_Poste IN (".$IdPosteCoordinateurEquipe.") 
					)
				AND rh_personne_hs.Etat2=1
				AND rh_personne_hs.Etat3=0
				AND rh_personne_hs.Etat4=0
				)
				OR 
				(CONCAT(rh_personne_hs.Id_Prestation,'_',rh_personne_hs.Id_Pole) IN 
					(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
					FROM new_competences_personne_poste_prestation 
					WHERE Id_Personne=".$Id_Personne."
					AND Id_Poste IN (".$IdPosteCoordinateurProjet.") 
					)
				AND rh_personne_hs.Etat3=1
				AND rh_personne_hs.Etat4=0
				)
			)
					";
	}
	else{
		$req.="(SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_hs.Id_Prestation) IN 
			(
				SELECT Id_Plateforme 
				FROM new_competences_personne_poste_plateforme
				WHERE Id_Personne=".$Id_Personne." 
				AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
			)
			AND rh_personne_hs.Etat4=1
			AND rh_personne_hs.DatePriseEnCompteRH<='0001-01-01'
			";
		
	}
	$result=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($result);
	
	return $nbResulta;
}

//Renvoi le nombre de transfert EC à valider par les managers
function NombreTransfertECArriveeManager($Id_Personne){
	global $bdd;
	global $IdPosteResponsableRH;
	global $IdPosteAssistantRH;
	global $IdPosteAideRH;
	global $IdPosteResponsablePlateforme;
	global $IdPosteChefEquipe;
	global $IdPosteCoordinateurEquipe;
	global $IdPosteCoordinateurProjet;
	global $IdPosteResponsableProjet;
	
	$req="SELECT rh_personne_mouvement.Id
			FROM rh_personne_mouvement
			WHERE Suppr=0
			AND EtatValidation=0 
			AND CONCAT(rh_personne_mouvement.Id_Prestation,'_',rh_personne_mouvement.Id_Pole) IN 
					(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
					FROM new_competences_personne_poste_prestation 
					WHERE Id_Personne=".$Id_Personne."
					AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.") 
					) ";

	$result=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($result);
	
	return $nbResulta;
}

//Renvoi le nombre de départ validé à prendre en compte par les managers
function NombreTransfertECDepartManager($Id_Personne){
	global $bdd;
	global $IdPosteResponsableRH;
	global $IdPosteAssistantRH;
	global $IdPosteAideRH;
	global $IdPosteResponsablePlateforme;
	global $IdPosteChefEquipe;
	global $IdPosteCoordinateurEquipe;
	global $IdPosteCoordinateurProjet;
	global $IdPosteResponsableProjet;
	
	$req="SELECT rh_personne_mouvement.Id
			FROM rh_personne_mouvement
			WHERE Suppr=0
			AND EtatValidation<>0
			AND DatePriseEnCompteDemandeur<='0001-01-01' ";
	$req.="AND CONCAT(rh_personne_mouvement.Id_PrestationDepart,'_',rh_personne_mouvement.Id_PoleDepart) IN 
				(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
				FROM new_competences_personne_poste_prestation 
				WHERE Id_Personne=".$Id_Personne."
				AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.") 
				) ";
	$result=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($result);
	
	return $nbResulta;
}

//Renvoi le nombre de transfert EC à prendre en compte par les RH
function NombreTransfertECRH($Id_Personne){
	global $bdd;
	global $IdPosteResponsableRH;
	global $IdPosteAssistantRH;
	global $IdPosteAideRH;
	global $IdPosteResponsablePlateforme;
	global $IdPosteChefEquipe;
	global $IdPosteCoordinateurEquipe;
	global $IdPosteCoordinateurProjet;
	global $IdPosteResponsableProjet;
	
	$req="SELECT rh_personne_mouvement.Id
			FROM rh_personne_mouvement
			WHERE Suppr=0 
			AND rh_personne_mouvement.DatePriseEnCompteRH<='0001-01-01' 
			AND EtatValidation=1
			AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_mouvement.Id_Prestation) IN 
			(
				SELECT Id_Plateforme 
				FROM new_competences_personne_poste_plateforme
				WHERE Id_Personne=".$Id_Personne." 
				AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
			) ";

	$result=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($result);
	
	return $nbResulta;
}

//Renvoi le nombre de transfert EC à prendre en compte par les RH
function NombreMouvementHorsPlateformeRH($Id_Personne){
	global $bdd;
	global $IdPosteResponsableRH;
	global $IdPosteAssistantRH;
	global $IdPosteAideRH;
	global $IdPosteResponsablePlateforme;
	global $IdPosteChefEquipe;
	global $IdPosteCoordinateurEquipe;
	global $IdPosteCoordinateurProjet;
	global $IdPosteResponsableProjet;
	
	$req="SELECT rh_personne_mouvement.Id
			FROM rh_personne_mouvement
			WHERE Suppr=0 
			AND EtatValidation=0
			AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_mouvement.Id_Prestation) <> 1 ";

	$result=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($result);
	
	return $nbResulta;
}

//Renvoi le nombre d'absences à prendre en compte
function NombreAbsAPrendreEnCompte($Id_Personne,$Manager){
	global $bdd;
	global $IdPosteResponsableRH;
	global $IdPosteAssistantRH;
	global $IdPosteAideRH;
	global $IdPosteResponsablePlateforme;
	global $IdPosteChefEquipe;
	global $IdPosteCoordinateurEquipe;
	global $IdPosteCoordinateurProjet;
	global $IdPosteResponsableProjet;
	
	$req="SELECT rh_personne_demandeabsence.Id
			FROM rh_personne_demandeabsence
			WHERE Suppr=0 
			AND Conge=0 
			";
	if($Manager==0){
		$req.="
			AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_demandeabsence.Id_Prestation) IN 
			(
				SELECT Id_Plateforme 
				FROM new_competences_personne_poste_plateforme
				WHERE Id_Personne=".$Id_Personne." 
				AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
			)
			AND rh_personne_demandeabsence.DatePriseEnCompteRH<='0001-01-01'
			";
	}
	else{
		$req.="AND ((CONCAT(rh_personne_demandeabsence.Id_Prestation,'_',rh_personne_demandeabsence.Id_Pole) IN 
					(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
					FROM new_competences_personne_poste_prestation 
					WHERE Id_Personne=".$Id_Personne."
					AND Id_Poste IN (".$IdPosteChefEquipe.") 
					)
					AND rh_personne_demandeabsence.DatePriseEnCompteN1<='0001-01-01'
					)
					OR
					(CONCAT(rh_personne_demandeabsence.Id_Prestation,'_',rh_personne_demandeabsence.Id_Pole) IN 
					(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
					FROM new_competences_personne_poste_prestation 
					WHERE Id_Personne=".$Id_Personne."
					AND Id_Poste IN (".$IdPosteCoordinateurEquipe.") 
					)
					AND rh_personne_demandeabsence.DatePriseEnCompteN2<='0001-01-01'
					)
					)
					";
	}
	$result=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($result);
	
	return $nbResulta;
}

//Renvoi le montant d'une astreinte en fonction du jour et du nombre d'heure 
function MontantAstreinte($Id_Plateforme,$DateAstreinte,$NbHeure1,$NbHeure2,$NbHeure3){
	global $bdd;
	$montant=0;
	//Récupération du barème
	$req="SELECT ForfaitWeekend,ForfaitSemaine,Samedi,Dimanche,JourFerie FROM rh_bareme_astreinte WHERE Suppr=0 AND Id_Plateforme=".$Id_Plateforme;
	$result=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($result);
	if($nbResulta>0){
		$rowBareme=mysqli_fetch_array($result);
		
		$tabDate = explode('-', $DateAstreinte);
		$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
		$jourSemaine = date('w', $timestamp);
		
		//Si Weekend
		if($jourSemaine==0 || $jourSemaine==6 || estJourFerie($DateAstreinte,$Id_Plateforme)){
			$montant=$rowBareme['ForfaitWeekend'];
			$montantIntervention=0;
			
			if(estJourFerie($DateAstreinte,$Id_Plateforme)){$montantIntervention=$rowBareme['JourFerie'];}
			elseif($jourSemaine==6){$montantIntervention=$rowBareme['Samedi'];}
			elseif($jourSemaine==0){$montantIntervention=$rowBareme['Dimanche'];}
			$NbHeure1=str_replace(":00","",$NbHeure1);
			$NbHeure1=str_replace(":",".",$NbHeure1);
			if($NbHeure1>0){
				$nbIntervention=ceil($NbHeure1/4);
				$montant=$montant+($nbIntervention*$montantIntervention);
			}
			$NbHeure2=str_replace(":00","",$NbHeure2);
			$NbHeure2=str_replace(":",".",$NbHeure2);
			if($NbHeure2>0){
				$nbIntervention=ceil($NbHeure2/4);
				$montant=$montant+($nbIntervention*$montantIntervention);
			}
			$NbHeure3=str_replace(":00","",$NbHeure3);
			$NbHeure3=str_replace(":",".",$NbHeure3);
			if($NbHeure3>0){
				$nbIntervention=ceil($NbHeure3/4);
				$montant=$montant+($nbIntervention*$montantIntervention);
			}
		}
		else{
			$montant=$rowBareme['ForfaitSemaine'];
		}
		
	}
	return $montant;
}

 function Ajouter_Heures($heure1,$heure2,$heure3){
	 $secondes1=heure_to_secondes($heure1); 
	 $secondes2=heure_to_secondes($heure2); 
	 $secondes3=heure_to_secondes($heure3); 
	 $somme=$secondes1+$secondes2+$secondes3; 
	 //transfo en h:i:s 
	 $s=$somme % 60; 
	 //reste de la division en minutes => secondes 
	 $m1=($somme-$s) / 60; 
	 //minutes totales 
	 $m=$m1 % 60;
	 //reste de la division en heures => minutes 
	 $h=($m1-$m) / 60; 
	 //heures 
	 if($m==0){$m="";}
	 else{$m=".".$m;}
	 $resultat=$h.$m; 
	 return $resultat; 
} 
function heure_to_secondes($heure){
	$array_heure=explode(":",$heure); 
	$secondes=3600*$array_heure[0]+60*$array_heure[1]+$array_heure[2]; 
	return $secondes; 
}

function NiveauValidationCongesPrestation($Id_Prestation){
	global $bdd;
	
	$Niveau=2;
	$req="SELECT NbNiveauValidationConges FROM new_competences_prestation WHERE Id=".$Id_Prestation;
	$result=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($result);
	if($nbResulta>0){
		$rowPresta=mysqli_fetch_array($result);
		if($rowPresta['NbNiveauValidationConges']==1){$Niveau=1;}
	}
	return $Niveau;
}

function EstEnVacationCeJour($Id_Personne,$DateJour){
	global $bdd;
	$NbHeures=0;
	$tabDate = explode('-', $DateJour);
	$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
	$jourSemaine = date('w', $timestamp);
	$Id_Vacation=0;
	
	//Récupérer les valeurs sur la prestaton actuelle 
	$req="SELECT Id_Prestation,Id_Pole 
			FROM rh_personne_mouvement
			LEFT JOIN new_competences_prestation
			ON rh_personne_mouvement.Id_Prestation=new_competences_prestation.Id
			WHERE rh_personne_mouvement.DateDebut<='".$DateJour."'
			AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$DateJour."')
			AND rh_personne_mouvement.EtatValidation=1 
			AND rh_personne_mouvement.Id_Personne=".$Id_Personne." ";
	$result=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($result);
	
	if($nbResulta>0){
		$rowPresta=mysqli_fetch_array($result);
		
		//Récupérer l'Id_vacation de cette journée en fonction
		$Couleur=TravailCeJourDeSemaine($DateJour,$Id_Personne);
		if($Couleur<>""){
			$Id_Vacation=IdVacationCeJourDeSemaine($DateJour,$Id_Personne);
			$Id_Contenu=estJour_Fixe_Id($DateJour,$Id_Personne);
			if($Id_Contenu<>""){$Id_Vacation=0;}
			
			//Vérifier si la personne n'a pas une vacation particulière ce jour là 
			$Id_Contenu=VacationPersonne($DateJour,$Id_Personne,$rowPresta['Id_Prestation'],$rowPresta['Id_Pole']);
			if($Id_Contenu>0){$Id_Vacation=$Id_Contenu;}
			//Vérifier si la personne n'est pas absente ou en congé ce jour là 
			$reqAbs="SELECT rh_absence.Id
						FROM rh_absence 
						LEFT JOIN rh_personne_demandeabsence 
						ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
						WHERE rh_personne_demandeabsence.Id_Personne=".$Id_Personne." 
						AND rh_absence.DateFin>='".$DateJour."' 
						AND rh_absence.DateDebut<='".$DateJour."' 
						AND rh_personne_demandeabsence.Suppr=0 
						AND rh_absence.Suppr=0  
						AND EtatN1<>-1
						AND EtatN2<>-1
						AND (EtatN2=1 OR DatePriseEnCompteRH>'0001-01-01') ";
			$resultAbs=mysqli_query($bdd,$reqAbs);
			$nbAbs=mysqli_num_rows($resultAbs);
			if($nbAbs>0){$Id_Vacation=0;}
		}
	}
	return $Id_Vacation;
}

function NombreHeuresJournee($Id_Personne,$DateJour){
	global $bdd;
	$NbHeures=0;
	$tabDate = explode('-', $DateJour);
	$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
	$jourSemaine = date('w', $timestamp);
	$Id_Vacation=0;
	
	//Récupérer les valeurs sur la prestaton actuelle 
	$req="SELECT Id_Prestation,Id_Pole 
			FROM rh_personne_mouvement
			WHERE rh_personne_mouvement.DateDebut<='".$DateJour."'
			AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$DateJour."')
			AND rh_personne_mouvement.EtatValidation=1 
			AND rh_personne_mouvement.Suppr=0
			AND rh_personne_mouvement.Id_Personne=".$Id_Personne." ";
	$result=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($result);
	
	if($nbResulta>0){
		$rowPresta=mysqli_fetch_array($result);
		
		//Récupérer l'Id_vacation de cette journée en fonction
		$Couleur=TravailCeJourDeSemaine($DateJour,$Id_Personne);
		if($Couleur<>""){
			$Id_Vacation=1;
			$Id_Contenu=estJour_Fixe_Id($DateJour,$Id_Personne);
			if($Id_Contenu<>""){$Id_Vacation=$Id_Contenu;}
			
			//Vérifier si la personne n'a pas une vacation particulière ce jour là 
			$Id_Contenu=IdVacationCeJourDeSemaine($DateJour,$Id_Personne);
			if($Id_Contenu>0){$Id_Vacation=$Id_Contenu;}
			
			$Id_Contenu=VacationPersonne($DateJour,$Id_Personne,$rowPresta['Id_Prestation'],$rowPresta['Id_Pole']);
			if($Id_Contenu>0){$Id_Vacation=$Id_Contenu;}
				
			//Vérifier si la personne n'est pas absente ou en congé ce jour là 
			$reqAbs="SELECT rh_absence.Id
						FROM rh_absence 
						LEFT JOIN rh_personne_demandeabsence 
						ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
						WHERE rh_personne_demandeabsence.Id_Personne=".$Id_Personne." 
						AND rh_absence.DateFin>='".$DateJour."' 
						AND rh_absence.DateDebut<='".$DateJour."' 
						AND rh_personne_demandeabsence.Suppr=0 
						AND rh_absence.Suppr=0  
						AND EtatN1<>-1
						AND EtatN2<>-1
						AND (EtatN2=1 OR DatePriseEnCompteRH>'0001-01-01') ";
			$resultAbs=mysqli_query($bdd,$reqAbs);
			$nbAbs=mysqli_num_rows($resultAbs);
			if($nbAbs>0){$Id_Vacation=0;}
			
			if($Id_Vacation>0){
				//Calcul du nombre d'heure de la journée en fonction de sa vacation, de son contrat et de sa prestation 
				$tabContrat=PointagePersonneContrat($DateJour,$Id_Personne,$Id_Vacation,$jourSemaine);
				if(sizeof($tabContrat)>0){
					$NbHeures=$tabContrat[0]+$tabContrat[1]+$tabContrat[2];
				}
				else{
					$tab=PointagePrestationVacation($rowPresta['Id_Prestation'],$rowPresta['Id_Pole'],$Id_Vacation,$jourSemaine,$DateJour);
					if(sizeof($tab)>0){
						$NbHeures=$tab[0]+$tab[1]+$tab[2]+$tab[4];
					}
				}
			}
			//Ne pas compter les astreintes 
			
			//Prendre en compte uniquement les infos si pointage exceptionnel
			$tab=PointagePersonneExceptionnel($Id_Personne,$rowPresta['Id_Prestation'],$rowPresta['Id_Pole'],$DateJour);
			if(sizeof($tab)>0){
				if($tab[0]+$tab[1]+$tab[2]+$tab[4]+$tab[6]>0 || $tab[5]==1){
					$NbHeures=$tab[0]+$tab[1]+$tab[2]+$tab[4]+$tab[6];
				}
			}
			
		}
		//Ajouter les Heures supplémentaires 
		$req="SELECT SUM(Nb_Heures_Jour) AS NbJour,SUM(Nb_Heures_Nuit) AS NbNuit
				FROM rh_personne_hs
				WHERE Suppr=0 
				AND Id_Personne=".$Id_Personne." 
				AND IF(DateRH>'0001-01-01',DateRH,DateHS)='".$DateJour."' 
				AND Etat4=1
				";
		$resultHS=mysqli_query($bdd,$req);
		$nbHS=mysqli_num_rows($resultHS);
		if($nbHS>0){
			$rowHS=mysqli_fetch_array($resultHS);
			$NbHeures=$NbHeures+$rowHS['NbJour']+$rowHS['NbNuit'];
		}
	}
	return $NbHeures;
}
function NombreHeuresSemaine($Id_Personne,$DateJour){
	$tabDate = explode('-', $DateJour);
	$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
	$jourSemaine = date('w', $timestamp);
	//1er jour de la semaine 
	if($jourSemaine==1){
		$PremierJourSemaine=$DateJour;
	}
	else{
		$PremierJourSemaine=date('Y-m-d',strtotime($DateJour." last Monday"));
	}
	//Dernier jour de la semaine 
	$DernierJourSemaine=date('Y-m-d',strtotime($PremierJourSemaine." next Sunday"));
	
	$NbHeures=0;
	for($laDate=$PremierJourSemaine;$laDate<=$DernierJourSemaine;$laDate=date('Y-m-d',strtotime($laDate." +1 day"))){
		$NbHeures+=NombreHeuresJournee($Id_Personne,$laDate);
	}
	return $NbHeures;
}

//Renvoi le pointage de la personne en fonction de son contrat
function HorairesPersonneContrat($DateJour,$Id_Personne,$Id_Vacation,$JourSemaine){
	global $bdd;
	
	$tab=array();
	$reqContrat="SELECT Id,Id_Personne,DateDebut,DateFin,Id_TempsTravail,
				(SELECT EstSalarie FROM rh_typecontrat WHERE rh_typecontrat.Id=rh_personne_contrat.Id_TypeContrat) AS EstSalarie
				FROM rh_personne_contrat
				WHERE Suppr=0
				AND DateDebut<='".$DateJour."'
				AND (DateFin>='".$DateJour."' OR DateFin<='0001-01-01' )
				AND Id_Personne=".$Id_Personne."
				AND TypeDocument IN ('Nouveau','Avenant')
				ORDER BY DateDebut DESC
				";

	$result=mysqli_query($bdd,$reqContrat);
	$nb=mysqli_num_rows($result);	
	if($nb>0){
		$rowContrat=mysqli_fetch_array($result);
		if($rowContrat['Id_TempsTravail']==10){
			if($JourSemaine<>0 && $JourSemaine<>6){
				$tab[0]="00:00:00";
			}
			else{
				$tab[0]="00:00:00";
			}
			$tab[1]="00:00:00";
		}
		else{
			//Verifier si temps partiel 
			$reqTP = "SELECT Id,NbHeureJour, NbHeureEJ, NbHeureEN, NbHeurePause,HeureDebut,HeureFin,Teletravail 
				FROM rh_personne_contrat_tempspartiel 
				WHERE Id_Personne_Contrat=".$rowContrat['Id']." 
				AND Suppr=0
				AND JourSemaine=".$JourSemaine." 
				AND (NbHeureJour+NbHeureEJ+NbHeureEN+NbHeurePause)>0
				AND Id_Vacation=".$Id_Vacation."";
			$resultTP=mysqli_query($bdd,$reqTP);
			$nbTP=mysqli_num_rows($resultTP);	
			if($nbTP>0){
				$rowTP=mysqli_fetch_array($resultTP);
				$tab[0]=$rowTP['HeureDebut'];
				$tab[1]=$rowTP['HeureFin'];
			}
		}
	}
	
	
	return $tab;
}

//Renvoi le pointage d'une prestation pour une vacation pour un jour de la semaine
function HorairesPrestationVacation($Id_Prestation,$Id_Pole,$Id_Vacation,$JourSemaine,$DateJour){
	global $bdd;
	$tab=array();
	$req="SELECT NbHeureJ,NbHeureEJ,NbHeureEN,NbHeurePause,HeureDebut,HeureFin,NbHeureFOR  
		FROM rh_prestation_vacation
		WHERE Id_Prestation=".$Id_Prestation." 
		AND Id_Pole=".$Id_Pole."
		AND DateDebut<='".$DateJour."'
		AND (DateFin<='0001-01-01' OR DateFin>='".$DateJour."')
		AND Suppr=0
		AND Id_Vacation=".$Id_Vacation."
		AND JourSemaine=".$JourSemaine." ";
	$result=mysqli_query($bdd,$req);
	$nb=mysqli_num_rows($result);	
	if($nb>0){
		$rowVac=mysqli_fetch_array($result);
		$tab[0]=$rowVac['HeureDebut'];
		$tab[1]=$rowVac['HeureFin'];
	}
	return $tab;
}

function HorairesJournee($Id_Personne,$DateJour){
	global $bdd;
	$tab=array();
	$tabDate = explode('-', $DateJour);
	$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
	$jourSemaine = date('w', $timestamp);
	$Id_Vacation=0;
	
	//Récupérer les valeurs sur la prestaton actuelle 
	$req="SELECT Id_Prestation,Id_Pole 
			FROM rh_personne_mouvement
			WHERE rh_personne_mouvement.DateDebut<='".$DateJour."'
			AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$DateJour."')
			AND rh_personne_mouvement.EtatValidation=1 
			AND rh_personne_mouvement.Suppr=0 
			AND rh_personne_mouvement.Id_Personne=".$Id_Personne."";
	$result=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($result);
	
	if($nbResulta>0){
		$rowPresta=mysqli_fetch_array($result);
		
		//Récupérer l'Id_vacation de cette journée en fonction
		$Couleur=TravailCeJourDeSemaine($DateJour,$Id_Personne);
		if($Couleur<>""){
			$Id_Vacation=1;
			
			$Id_Contenu=IdVacationCeJourDeSemaine($DateJour,$Id_Personne);
			if($Id_Contenu<>""){$Id_Vacation=$Id_Contenu;}

			$Id_Contenu=estJour_Fixe_Id($DateJour,$Id_Personne);
			if($Id_Contenu<>""){$Id_Vacation=$Id_Contenu;}
			
			
			
			//Vérifier si la personne n'est pas absente ou en congé ce jour là toute la journée
			$reqAbs="SELECT rh_absence.Id
						FROM rh_absence 
						LEFT JOIN rh_personne_demandeabsence 
						ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
						WHERE rh_personne_demandeabsence.Id_Personne=".$Id_Personne." 
						AND rh_absence.DateFin>='".$DateJour."' 
						AND rh_absence.DateDebut<='".$DateJour."' 
						AND rh_personne_demandeabsence.Suppr=0 
						AND rh_absence.NbHeureAbsJour=0
						AND rh_absence.NbHeureAbsNuit=0
						AND rh_absence.Suppr=0  
						AND EtatN2=1 ";
			$resultAbs=mysqli_query($bdd,$reqAbs);
			$nbAbs=mysqli_num_rows($resultAbs);
			if($nbAbs>0){$Id_Vacation=0;}
			
			//Vérifier si la personne n'a pas une vacation particulière ce jour là 
			if($Id_Vacation<>0){
				$Id_Contenu=VacationPersonne($DateJour,$Id_Personne,$rowPresta['Id_Prestation'],$rowPresta['Id_Pole']);
				if($Id_Contenu>0){$Id_Vacation=$Id_Contenu;}
			}
			
			//Calcul du nombre d'heure de la journée en fonction de sa vacation, de son contrat et de sa prestation 
			$tab=HorairesPersonneContrat($DateJour,$Id_Personne,$Id_Vacation,$jourSemaine);
			if(sizeof($tab)==0){
				$tab=HorairesPrestationVacation($rowPresta['Id_Prestation'],$rowPresta['Id_Pole'],$Id_Vacation,$jourSemaine,$DateJour);
			}
		}
	}
	
	return $tab;
}

//Vérifie si la personne est sur une prestation / pôle à un jour donnée 
function appartientPrestation($DateJour,$Id_Personne,$Id_Prestation,$Id_Pole){
	global $bdd;
	$appartient = 0;
	
	$req = "SELECT Id
		FROM rh_personne_mouvement 
		WHERE rh_personne_mouvement.DateDebut<='".$DateJour."'
		AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$DateJour."')
		AND rh_personne_mouvement.EtatValidation=1 
		AND Id_Personne=".$Id_Personne."
		AND Suppr=0
		AND rh_personne_mouvement.Id_Prestation=".$Id_Prestation."
		AND rh_personne_mouvement.Id_Pole=".$Id_Pole."";
	$resultPersonne=mysqli_query($bdd,$req);
	$nbPersonne=mysqli_num_rows($resultPersonne);
	if($nbPersonne>0){$appartient=1;}
	return $appartient;
}

//Récupère les vacatons d'une prestation pole
function VacationPrestation($Id_Prestation,$Id_Pole,$JourSemaine,$Id_Vacation,$Champs,$DateJour){
	global $bdd;
	$Valeur = "";
	
	$req = "SELECT NbHeureJ,NbHeureEJ,NbHeureEN,NbHeurePause,HeureDebut,HeureFin,NbHeureFOR
		FROM rh_prestation_vacation 
		WHERE Suppr=0
		AND DateDebut<='".$DateJour."'
		AND (DateFin<='0001-01-01' OR DateFin>='".$DateJour."')
		AND JourSemaine=".$JourSemaine."
		AND Id_Vacation=".$Id_Vacation."
		AND Id_Prestation=".$Id_Prestation."
		AND Id_Pole=".$Id_Pole." ";
	$result=mysqli_query($bdd,$req);
	$nb=mysqli_num_rows($result);
	if($nb>0){
		$row=mysqli_fetch_array($result);
		if($row[$Champs]>0){
			$Valeur = $row[$Champs];
		}
	}
	return $Valeur;
}

function ReaffecterDemandeConge(){
	global $bdd;
	$req="UPDATE rh_personne_demandeabsence
			SET EtatN1=0,
			Id_Prestation=(
				SELECT rh_personne_mouvement.Id_Prestation 
				FROM rh_personne_mouvement
				WHERE rh_personne_mouvement.Suppr=0
				AND rh_personne_mouvement.Id_Personne=rh_personne_demandeabsence.Id_Personne
				AND rh_personne_mouvement.EtatValidation=1
				AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
				AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."')
				LIMIT 1
			),
			Id_Pole=(
				SELECT rh_personne_mouvement.Id_Pole 
				FROM rh_personne_mouvement
				WHERE rh_personne_mouvement.Suppr=0
				AND rh_personne_mouvement.Id_Personne=rh_personne_demandeabsence.Id_Personne
				AND rh_personne_mouvement.EtatValidation=1
				AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
				AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."')
				LIMIT 1
			)
			WHERE Suppr=0
			AND Conge=1
			AND EtatN1<>-1
			AND EtatN2=0
			AND CONCAT(Id_Prestation,'_',Id_Pole) NOT IN (
				SELECT CONCAT(rh_personne_mouvement.Id_Prestation,'_',rh_personne_mouvement.Id_Pole) 
				FROM rh_personne_mouvement
				WHERE rh_personne_mouvement.Suppr=0
				AND rh_personne_mouvement.Id_Personne=rh_personne_demandeabsence.Id_Personne
				AND rh_personne_mouvement.EtatValidation=1
				AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
				AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."')
			)";
	$result=mysqli_query($bdd,$req);
}

function AppartientSuiteAstreinte($DateAstreinte,$Id_Personne){
	global $bdd;
	$estUneSuite=1;
	
	$tabDate = explode('-', $DateAstreinte);
	$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
	$jourSemaine = date('w', $timestamp);
	//1er jour de la semaine 
	if($jourSemaine==1){
		$lundi=$DateAstreinte;
	}
	else{
		$lundi=date('Y-m-d',strtotime($DateAstreinte." last Monday"));
	}
	//Dernier jour de la semaine 
	$dimanche=date('Y-m-d',strtotime($lundi." next Sunday"));
	
	for($tmpDate=$lundi;$tmpDate<=$dimanche;$tmpDate=date('Y-m-d',strtotime($tmpDate." +1 day"))){
		$req="SELECT Id
			FROM rh_personne_rapportastreinte
			WHERE Suppr=0 
			AND DateAstreinte='".$tmpDate."'
			AND Id_Personne=".$Id_Personne." 
			AND EtatN1<>-1
			AND EtatN2<>-1
			";
		$result=mysqli_query($bdd,$req);
		$nb=mysqli_num_rows($result);
		if($nb==0){$estUneSuite=0;}
	}
	return $estUneSuite;
}

function IdContratEC($Id_Personne){
	global $bdd;
	$Id=0;
	
	$req="SELECT Id
		FROM rh_personne_contrat
		WHERE Suppr=0
		AND DateDebut<='".date('Y-m-d')."'
		AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
		AND TypeDocument IN ('Nouveau','Avenant')
		AND Id_Personne=".$Id_Personne."
		ORDER BY DateDebut DESC, Id DESC ";
	$result=mysqli_query($bdd,$req);
	$nb=mysqli_num_rows($result);
	if($nb>0){
		$row=mysqli_fetch_array($result);
		$Id=$row['Id'];
	}
	return $Id;
}

function Id_TypeContrat($Id_Contrat){
	global $bdd;
	$Id=0;
	
	$req="SELECT Id_TempsTravail
	FROM rh_personne_contrat
	WHERE Id=".$Id_Contrat." ";
	$result=mysqli_query($bdd,$req);
	$nb=mysqli_num_rows($result);
	if($nb>0){
		$row=mysqli_fetch_array($result);
		$Id=$row['Id_TempsTravail'];
	}
	return $Id;
}

function TypeContrat($Id_Contrat){
	global $bdd;
	$Id=0;
	
	$req="SELECT (SELECT Libelle FROM rh_typecontrat WHERE Id=Id_TypeContrat) AS TypeContrat
	FROM rh_personne_contrat
	WHERE Id=".$Id_Contrat." ";
	$result=mysqli_query($bdd,$req);
	$nb=mysqli_num_rows($result);
	if($nb>0){
		$row=mysqli_fetch_array($result);
		$Id=$row['TypeContrat'];
	}
	return $Id;
}

function TypeContrat2($Id_Contrat){
	global $bdd;
	$Id=0;
	
	$req="SELECT Id_TypeContrat
	FROM rh_personne_contrat
	WHERE Id=".$Id_Contrat." ";
	$result=mysqli_query($bdd,$req);
	$nb=mysqli_num_rows($result);
	if($nb>0){
		$row=mysqli_fetch_array($result);
		$Id=$row['Id_TypeContrat'];
	}
	return $Id;
}

function AgenceInterimContrat($Id_Contrat){
	global $bdd;
	$tab=0;
	
	$req="SELECT (SELECT Libelle FROM rh_agenceinterim WHERE Id=Id_AgenceInterim) AS AgenceInterim,
	TypeCoeff,
	CoeffFacturationAgence,
	Coeff,
	TauxHoraire
	FROM rh_personne_contrat
	WHERE Id=".$Id_Contrat." ";

	$result=mysqli_query($bdd,$req);
	$nb=mysqli_num_rows($result);
	if($nb>0){
		$row=mysqli_fetch_array($result);
		$tab=array();
		
		$tab[0]=$row['AgenceInterim'];
		$tab[1]=$row['TypeCoeff'];
		$tab[2]=$row['CoeffFacturationAgence'];
		$tab[3]=$row['Coeff'];
		$tab[4]=$row['TauxHoraire'];
	}
	return $tab;
}

function IdMetierEC($Id_Personne){
	global $bdd;
	$Id=0;
	
	$req="SELECT Id_Metier
		FROM rh_personne_contrat
		WHERE Suppr=0
		AND DateDebut<='".date('Y-m-d')."'
		AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
		AND TypeDocument IN ('Nouveau','Avenant')
		AND Id_Personne=".$Id_Personne."
		ORDER BY DateDebut DESC, Id DESC ";
	$result=mysqli_query($bdd,$req);
	$nb=mysqli_num_rows($result);
	if($nb>0){
		$row=mysqli_fetch_array($result);
		$Id=$row['Id_Metier'];
	}
	return $Id;
}

function IdContrat($Id_Personne,$date){
	global $bdd;
	$Id=0;
	
	$req="SELECT Id
		FROM rh_personne_contrat
		WHERE Suppr=0
		AND DateDebut<='".$date."'
		AND (DateFin>='".$date."' OR DateFin<='0001-01-01' )
		AND TypeDocument IN ('Nouveau','Avenant')
		AND Id_Personne=".$Id_Personne."
		ORDER BY DateDebut DESC, Id DESC ";
	$result=mysqli_query($bdd,$req);
	$nb=mysqli_num_rows($result);
	if($nb>0){
		$row=mysqli_fetch_array($result);
		$Id=$row['Id'];
	}
	return $Id;
}

function CentreDeCoutContratInitial($Id_Personne,$date){
	global $bdd;
	
	$Id_Prestation=0;
	$CentreDeCout="";
	
	$req="SELECT Id, TypeDocument, Id_Prestation, Id_ContratInitial,
		(SELECT CentreDeCout FROM new_competences_prestation WHERE Id=Id_Prestation) AS CentreDeCout
		FROM rh_personne_contrat
		WHERE Suppr=0
		AND DateDebut<='".$date."'
		AND (DateFin>='".$date."' OR DateFin<='0001-01-01' )
		AND TypeDocument IN ('Nouveau','Avenant')
		AND Id_Personne=".$Id_Personne."
		ORDER BY DateDebut DESC, Id DESC ";
	$result=mysqli_query($bdd,$req);
	$nb=mysqli_num_rows($result);
	if($nb>0){
		$row=mysqli_fetch_array($result);
		$Id_Prestation=$row['Id_Prestation'];
		if($Id_Prestation>0){
			$CentreDeCout=$row['CentreDeCout'];
		}
		
		if($row['TypeDocument']=='Avenant'){
			if($row['Id_ContratInitial']>0){
				$req="SELECT Id, TypeDocument, Id_Prestation,
					(SELECT CentreDeCout FROM new_competences_prestation WHERE Id=Id_Prestation) AS CentreDeCout
					FROM rh_personne_contrat
					WHERE Suppr=0
					AND Id=".$row['Id_ContratInitial']."
					ORDER BY DateDebut DESC, Id DESC ";
				$result=mysqli_query($bdd,$req);
				$nb=mysqli_num_rows($result);
				if($nb>0){
					$row=mysqli_fetch_array($result);
					$Id_Prestation=$row['Id_Prestation'];
					if($Id_Prestation>0){
						$CentreDeCout=$row['CentreDeCout'];
					}
				}
			}
		}
	}
	
	return $CentreDeCout;
}

function CentreDeCoutContratInitialDernierContrat($Id_Personne){
	global $bdd;
	
	$Id_Prestation=0;
	$CentreDeCout="";

	$req="SELECT Id, TypeDocument, Id_Prestation,Id_ContratInitial,
		(SELECT CentreDeCout FROM new_competences_prestation WHERE Id=Id_Prestation) AS CentreDeCout
		FROM rh_personne_contrat
		WHERE Suppr=0
		AND Id_Personne=".$Id_Personne."
		AND TypeDocument IN ('Nouveau','Avenant')
		ORDER BY DateDebut DESC, Id DESC
		";
	$result=mysqli_query($bdd,$req);
	$nb=mysqli_num_rows($result);
	if($nb>0){
		$row=mysqli_fetch_array($result);
		$Id_Prestation=$row['Id_Prestation'];
		if($Id_Prestation>0){
			$CentreDeCout=$row['CentreDeCout'];
		}
		
		if($row['TypeDocument']=='Avenant'){
			if($row['Id_ContratInitial']>0){
				$req="SELECT Id, TypeDocument, Id_Prestation,
					(SELECT CentreDeCout FROM new_competences_prestation WHERE Id=Id_Prestation) AS CentreDeCout
					FROM rh_personne_contrat
					WHERE Suppr=0
					AND Id=".$row['Id_ContratInitial']."
					ORDER BY DateDebut DESC, Id DESC ";
				$result=mysqli_query($bdd,$req);
				$nb=mysqli_num_rows($result);
				if($nb>0){
					$row=mysqli_fetch_array($result);
					$Id_Prestation=$row['Id_Prestation'];
					if($Id_Prestation>0){
						$CentreDeCout=$row['CentreDeCout'];
					}
				}
			}
		}
	}
	
	return $CentreDeCout;
}

function IdODMEC($Id_Personne){
	global $bdd;
	$Id=0;
	
	$req="SELECT Id
		FROM rh_personne_contrat
		WHERE Suppr=0
		AND DateDebut<='".date('Y-m-d')."'
		AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
		AND TypeDocument IN ('ODM')
		AND Id_Personne=".$Id_Personne."
		ORDER BY DateDebut DESC, Id DESC ";
	$result=mysqli_query($bdd,$req);
	$nb=mysqli_num_rows($result);
	if($nb>0){
		$row=mysqli_fetch_array($result);
		$Id=$row['Id'];
	}
	return $Id;
}

function ExcelAT($Id){
	global $bdd;
	
	//Ouvrir fichier
	$workbook = new PHPExcel_Reader_Excel2007();
	$excel = $workbook->load('Template_AT2.xlsx');
	$sheet = $excel->getSheetByName('Informations AT');

	$requete2="SELECT Id,Id_Personne,Id_Createur,Id_Prestation,Id_Pole,Id_Metier,Id_Lieu_AT,
		DateCreation,DateAT,HeureAT,Id_TypeContrat,DoutesCirconstances As Doutes,EvacuationVers,
		AutreVictime,TiersResponsable,Temoin,CoordonneesTemoins,1erePersonneAvertie,
		Adresse,CP,Ville,NumSecurite,DateNaissance,Anciennete,HeureDebutAM,HeureFinAM,HeureDebutPM,HeureFinPM,Id_TypeVehicule,
		ConditionClim,MauvaisEtatInfra,TrajetAller,HoraireTravail,ProblemeTechnique,CommentaireCirconstance,CommentaireCirconstance2,
		LieuAccident,SIRETClient,Activite,CommentaireNature,ArretDeTravail,
		DateConnaissanceAT,HeureConnaissanceAT,DoutesCirconstances,AutresInformations,
		(SELECT Libelle FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS Metier,
		(SELECT Libelle FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS TypeContrat,
		(SELECT LibelleEN FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS TypeContratEN,
		(SELECT Libelle FROM rh_lieu_at WHERE rh_lieu_at.Id=Id_Lieu_AT) AS LieuAT,
		(SELECT LibelleEN FROM rh_lieu_at WHERE rh_lieu_at.Id=Id_Lieu_AT) AS LieuATEN,
		(SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_Prestation) AS Prestation,
		(SELECT new_rh_etatcivil.Nom FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_at.Id_Personne) AS NomPersonne, 
		(SELECT new_rh_etatcivil.Prenom FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_at.Id_Personne) AS PrenomPersonne, 
		(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_at.Id_Createur) AS Demandeur,
		(SELECT CONCAT(LEFT(new_rh_etatcivil.Prenom, 1),LEFT(new_rh_etatcivil.Nom, 1),RIGHT(new_rh_etatcivil.Nom, 1)) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_at.Id_Createur) AS SigleDemandeur 
		FROM rh_personne_at
		WHERE Id=".$Id;
	$result=mysqli_query($bdd,$requete2);
	$rowAT=mysqli_fetch_array($result);

	$sheet->setCellValue('C9',utf8_encode(AfficheDateJJ_MM_AAAA($rowAT['DateAT'])));
	$sheet->setCellValue('J9',utf8_encode($rowAT['HeureAT']));
	$sheet->setCellValue('O9',utf8_encode($rowAT['TypeContrat']));

	$sheet->setCellValue('C11',utf8_encode($rowAT['NomPersonne']));
	$sheet->setCellValue('J11',utf8_encode($rowAT['PrenomPersonne']));

	$sheet->setCellValue('C13',utf8_encode($rowAT['Adresse']));
	$sheet->setCellValue('K13',utf8_encode($rowAT['CP']));
	$sheet->setCellValue('M13',utf8_encode($rowAT['Ville']));

	$sheet->setCellValue('C15',utf8_encode($rowAT['NumSecurite']));
	$sheet->setCellValue('J15',utf8_encode(AfficheDateJJ_MM_AAAA($rowAT['DateNaissance'])));
	$sheet->setCellValue('N15',utf8_encode($rowAT['Anciennete']));

	$sheet->setCellValue('B17',utf8_encode($rowAT['Metier']));

	if($_SESSION['Langue']=="FR"){$sheet->setCellValue('I17',utf8_encode("de ".str_replace(":","h",substr($rowAT['HeureDebutAM'],0,5))." à ".str_replace(":","h",substr($rowAT['HeureFinAM'],0,5))."  et  ".str_replace(":","h",substr($rowAT['HeureDebutPM'],0,5))." à ".str_replace(":","h",substr($rowAT['HeureFinPM'],0,5)).""));}
	else{$sheet->setCellValue('I17',utf8_encode("de ".str_replace(":","h",substr($rowAT['HeureDebutAM'],0,5))." à ".str_replace(":","h",substr($rowAT['HeureFinAM'],0,5))."  et  ".str_replace(":","h",substr($rowAT['HeureDebutPM'],0,5))." à ".str_replace(":","h",substr($rowAT['HeureFinPM'],0,5)).""));}

	$sheet->setCellValue('A19',utf8_encode($rowAT['LieuAccident']));
	$sheet->getStyle('A19')->getAlignment()->setWrapText(true);
	$sheet->setCellValue('L19',utf8_encode($rowAT['SIRETClient']));

	$sheet->setCellValue('D20',utf8_encode($rowAT['Prestation']));

	if($_SESSION['Langue']=="FR"){
	$req="SELECT Id,Libelle	
		FROM rh_lieu_at
		WHERE Suppr=0 OR Id=".$rowAT['Id_Lieu_AT']."
		ORDER BY Libelle
		";
	}
	else{
	$req="SELECT Id,LibelleEN AS Libelle	
		FROM rh_lieu_at
		WHERE Suppr=0 OR Id=".$rowAT['Id_Lieu_AT']."
		ORDER BY LibelleEN
		";	
	}
	$result=mysqli_query($bdd,$req);
	$nb=mysqli_num_rows($result);
	$Ligne=22;
	$nbLigne=0;

	//Inserer une ligne
	$nbLignePlus=$nb/2;
	$leNombre=0;
	for($i=0;$i<=$nbLignePlus;$i++){
		if($i>3){
		$leNombre++;
		}
	}
	if($leNombre>0){
		$sheet->insertNewRowBefore($Ligne+1, $leNombre);
	}
	if($nb>0){
		$nbLigne=0;
		$Col="A";
		$ColX="G";
		while($row=mysqli_fetch_array($result)){
			$sheet->setCellValue($Col.$Ligne,utf8_encode($row['Libelle']));
			if($row['Id']==$rowAT['Id_Lieu_AT']){$sheet->setCellValue($ColX.$Ligne,utf8_encode("X"));}
			if($Col=="A"){$Col="H";$ColX="O";}
			else{
				$Col="A";
				$ColX="G";
				$Ligne++;
				$nbLigne++;
			}
		}
	}

	$Ligne=$Ligne+1;
	$sheet->setCellValue('A'.$Ligne,utf8_encode($rowAT['Activite']));

	$Ligne=$Ligne+3;
	//Type de véhicule
	$req="SELECT Id,Libelle	
		FROM rh_typevehicule
		WHERE Suppr=0
		ORDER BY Libelle
		";
	$result=mysqli_query($bdd,$req);
	$nb=mysqli_num_rows($result);
	$typeVehicule="";
	if($nb>0){
		while($row=mysqli_fetch_array($result)){
			if($rowAT['Id_TypeVehicule']==$row['Id']){
				$typeVehicule.="X    ";
			}
			$typeVehicule.=$row['Libelle']."        ";
		}
	}
	$sheet->setCellValue('F'.$Ligne,utf8_encode($typeVehicule));
	$Ligne=$Ligne+1;
	if($rowAT['ConditionClim']==1){
		$sheet->setCellValue('F'.$Ligne,utf8_encode("X  Conditions climatiques particulières"));
	}
	else{
		$sheet->setCellValue('F'.$Ligne,utf8_encode("   Conditions climatiques particulières"));
	}
	$sheet->setCellValue('J'.$Ligne,utf8_encode($rowAT['CommentaireCirconstance']));

	$Ligne=$Ligne+1;
	if($rowAT['MauvaisEtatInfra']==1){
		$sheet->setCellValue('F'.$Ligne,utf8_encode("X  Mauvais état des infrastructures"));
	}
	else{
		$sheet->setCellValue('F'.$Ligne,utf8_encode("   Mauvais état des infrastructures"));
	}
	$Ligne=$Ligne+1;

	$Ligne=$Ligne+1;
	if($rowAT['HoraireTravail']==1){
		$sheet->setCellValue('F'.$Ligne,utf8_encode("X  Horaires de travail spécifiques"));
	}
	else{
		$sheet->setCellValue('F'.$Ligne,utf8_encode("   Horaires de travail spécifiques"));
	}
	$sheet->setCellValue('J'.$Ligne,utf8_encode($rowAT['CommentaireCirconstance2']));

	$Ligne=$Ligne+1;
	if($rowAT['ProblemeTechnique']==1){
		$sheet->setCellValue('F'.$Ligne,utf8_encode("X  Problème technique du véhicule accidenté"));
	}
	else{
		$sheet->setCellValue('F'.$Ligne,utf8_encode("   Problème technique du véhicule accidenté"));
	}

	$Ligne=$Ligne+2;
	$sheet->setCellValue('A'.$Ligne,utf8_encode($rowAT['CommentaireNature']));

	if($_SESSION['Langue']=="FR"){
	$req="SELECT Id,Libelle	
		FROM rh_typeobjet_at
		WHERE Suppr=0
		ORDER BY Libelle
		";
	}
	else{
	$req="SELECT Id,LibelleEN AS Libelle	
		FROM rh_typeobjet_at
		WHERE Suppr=0
		ORDER BY LibelleEN
		";	
	}
	$result=mysqli_query($bdd,$req);
	$nb=mysqli_num_rows($result);

	$req="SELECT Objet,Id_TypeObjet	
		FROM rh_personne_at_objet 
		WHERE Suppr=0 
		AND Id_Personne_AT=".$rowAT['Id']." ";
	$resultObj=mysqli_query($bdd,$req);
	$nbObj=mysqli_num_rows($resultObj);

	$Ligne=$Ligne+3;
	$nbLigne=0;

	//Inserer une ligne
	$nbLignePlus=$nb/2;
	$leNombre=0;
	for($i=0;$i<=$nbLignePlus;$i++){
		if($i>3){
		$leNombre++;
		}
	}

	if($leNombre>0){
		$sheet->insertNewRowBefore($Ligne+1, $leNombre);
	}
	if($nb>0){
		$nbLigne=0;
		$Col="A";
		$ColX="C";
		while($row=mysqli_fetch_array($result)){
			$sheet->setCellValue($Col.$Ligne,utf8_encode(str_replace("°","",$row['Libelle'])));
			if($nbObj>0){
				mysqli_data_seek($resultObj,0);
				while($rowObj=mysqli_fetch_array($resultObj)){
					if($rowObj['Id_TypeObjet']==$row['Id']){
						$sheet->setCellValue($ColX.$Ligne,utf8_encode($rowObj['Objet']));
					}
				}
			}
			if($Col=="A"){$Col="I";$ColX="K";}
			else{
				$Col="A";
				$ColX="C";
				$Ligne++;
				$nbLigne++;
			}
		}
	}

	$Ligne=$Ligne+1;

	if($_SESSION['Langue']=="FR"){
	$req="SELECT Id,Libelle,CoteGD	
		FROM rh_siege_lesion_at
		WHERE Suppr=0
		ORDER BY Libelle
		";
	}
	else{
	$req="SELECT Id,LibelleEN AS Libelle,CoteGD	
		FROM rh_siege_lesion_at
		WHERE Suppr=0
		ORDER BY LibelleEN
		";	
	}
	$result=mysqli_query($bdd,$req);
	$nb=mysqli_num_rows($result);

	$req="SELECT Id_SiegeLesion,AutreSiege,Gauche,Droite
			FROM rh_personne_at_siegelesion 
			WHERE Suppr=0 
			AND Id_Personne_AT=".$rowAT['Id']."
			";
	$resultSiege=mysqli_query($bdd,$req);
	$nbSiege=mysqli_num_rows($resultSiege);

	//Inserer une ligne
	$nbLignePlus=$nb/6;
	$leNombre=0;
	for($i=0;$i<=$nbLignePlus;$i++){
		if($i>3){
		$leNombre++;
		}
	}

	if($leNombre>0){
		$sheet->insertNewRowBefore($Ligne+1, $leNombre);
	}
	if($nb>0){
		$nbLigne=0;
		$Col="A";
		$ColX="B";
		while($row=mysqli_fetch_array($result)){
			$sheet->setCellValue($Col.$Ligne,utf8_encode(str_replace("°","",$row['Libelle'])));
			if($row['Libelle']=="Autres" || $row['Libelle']=="Autre" || $row['Libelle']=="Others" || $row['Libelle']=="Other" || 
			$row['Libelle']=="°Autres" || $row['Libelle']=="°Autre" || $row['Libelle']=="°Others" || $row['Libelle']=="°Other"){
				$sheet->getStyle($ColX.$Ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'e7e6e6'))));
				$sheet->getStyle($ColX.$Ligne)->applyFromArray(array('borders' => array('bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => '000000')))));
			}
			$Trouve=0;
			if($nbSiege>0){
				mysqli_data_seek($resultSiege,0);
				$Trouve=0;
				while($rowSiege=mysqli_fetch_array($resultSiege)){
					if($rowSiege['Id_SiegeLesion']==$row['Id']){
						$Trouve=1;
						if($rowSiege['AutreSiege']<>""){
							$sheet->setCellValue($ColX.$Ligne,utf8_encode($rowSiege['AutreSiege']));
						}
						else{
							if($row['CoteGD']==1){
								$siege="";
								if($_SESSION['Langue']=="FR"){
									if($rowSiege['Gauche']==1){$siege.="x G ";}else{$siege.="  G ";}
									if($rowSiege['Droite']==1){$siege.="x D ";}else{$siege.="  D ";}
								}
								else{
									if($rowSiege['Gauche']==1){$siege.="x L ";}else{$siege.="  L ";}
									if($rowSiege['Droite']==1){$siege.="x R ";}else{$siege.="  R ";}
								}
								$sheet->setCellValue($ColX.$Ligne,utf8_encode($siege));
							}
							else{
								$sheet->setCellValue($ColX.$Ligne,utf8_encode("x"));
							}
						}
					}
				}
			}
			if($Trouve==0){
				if($row['CoteGD']==1){
					if($_SESSION['Langue']=="FR"){
						$sheet->setCellValue($ColX.$Ligne,utf8_encode("   G   D "));
					}
					else{
						$sheet->setCellValue($ColX.$Ligne,utf8_encode("   L   R "));
					}
				}
			}
			if($Col=="A"){$Col="C";$ColX="D";}
			elseif($Col=="C"){$Col="E";$ColX="F";}
			elseif($Col=="E"){$Col="G";$ColX="H";}
			elseif($Col=="G"){$Col="I";$ColX="J";}
			elseif($Col=="I"){$Col="K";$ColX="L";}
			else{
				$Col="A";
				$ColX="B";
				$Ligne++;
				$nbLigne++;
			}
		}
	}

	$Ligne=$Ligne+1;

	if($_SESSION['Langue']=="FR"){
	$req="SELECT Id,Libelle	
		FROM rh_nature_lesion
		WHERE Suppr=0
		ORDER BY Libelle
		";
	}
	else{
	$req="SELECT Id,LibelleEN AS Libelle	
		FROM rh_nature_lesion
		WHERE Suppr=0
		ORDER BY LibelleEN
		";	
	}
	$result=mysqli_query($bdd,$req);
	$nb=mysqli_num_rows($result);

	$req="SELECT Id_NatureLesion,AutreNature
			FROM rh_personne_at_nature_lesion 
			WHERE Suppr=0 
			AND Id_PersonneAT=".$rowAT['Id']."
			";
	$resultNature=mysqli_query($bdd,$req);
	$nbNature=mysqli_num_rows($resultNature);

	//Inserer une ligne
	$nbLignePlus=$nb/5;
	$leNombre=0;
	for($i=0;$i<=$nbLignePlus;$i++){
		if($i>2){
		$leNombre++;
		}
	}

	if($leNombre>0){
		$sheet->insertNewRowBefore($Ligne+1, $leNombre);
	}
	if($nb>0){
		$nbLigne=0;
		$Col="A";
		$ColX="C";
		while($row=mysqli_fetch_array($result)){
			$sheet->setCellValue($Col.$Ligne,utf8_encode(str_replace("°","",$row['Libelle'])));
			if($row['Libelle']=="Autres" || $row['Libelle']=="Autre" || $row['Libelle']=="Others" || $row['Libelle']=="Other" || 
			$row['Libelle']=="°Autres" || $row['Libelle']=="°Autre" || $row['Libelle']=="°Others" || $row['Libelle']=="°Other"){
				$sheet->getStyle($ColX.$Ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'e7e6e6'))));
				$sheet->getStyle($ColX.$Ligne)->applyFromArray(array('borders' => array('bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => '000000')))));
			}
			if($nbSiege>0){
				mysqli_data_seek($resultNature,0);
				while($rowNature=mysqli_fetch_array($resultNature)){
					if($rowNature['Id_NatureLesion']==$row['Id']){
						if($rowNature['AutreNature']<>""){
							$sheet->setCellValue($ColX.$Ligne,utf8_encode($rowNature['AutreNature']));
						}
						else{
							
							$sheet->setCellValue($ColX.$Ligne,utf8_encode("x"));
						}
					}
				}
			}
			if($Col=="A"){$Col="D";$ColX="F";}
			elseif($Col=="D"){$Col="G";$ColX="I";}
			elseif($Col=="G"){$Col="J";$ColX="L";}
			elseif($Col=="J"){$Col="M";$ColX="O";}
			else{
				$Col="A";
				$ColX="C";
				$Ligne++;
				$nbLigne++;
			}
		}
	}

	$Ligne=$Ligne+1;
	if($rowAT['ArretDeTravail']==0){
		$sheet->setCellValue('G'.$Ligne,utf8_encode('x'));
	}
	else{
		$sheet->setCellValue('C'.$Ligne,utf8_encode('x'));
	}
	$sheet->setCellValue('J'.$Ligne,utf8_encode($rowAT['EvacuationVers']));

	$Ligne=$Ligne+1;
	$sheet->setCellValue('C'.$Ligne,utf8_encode($rowAT['AutreVictime']));
	$sheet->setCellValue('J'.$Ligne,utf8_encode($rowAT['TiersResponsable']));

	$Ligne=$Ligne+1;
	$sheet->setCellValue('C'.$Ligne,utf8_encode($rowAT['Temoin']));
	$sheet->setCellValue('J'.$Ligne,utf8_encode($rowAT['CoordonneesTemoins']));

	$Ligne=$Ligne+1;
	$sheet->setCellValue('D'.$Ligne,utf8_encode($rowAT['1erePersonneAvertie']));

	$Ligne=$Ligne+1;
	$sheet->setCellValue('E'.$Ligne,utf8_encode(AfficheDateJJ_MM_AAAA($rowAT['DateConnaissanceAT'])));
	$sheet->setCellValue('L'.$Ligne,utf8_encode($rowAT['HeureConnaissanceAT']));

	$Ligne=$Ligne+2;
	$sheet->setCellValue('A'.$Ligne,utf8_encode($rowAT['Doutes']));
	$sheet->getStyle('A'.$Ligne)->getAlignment()->setWrapText(true);

	$Ligne=$Ligne+4;
	$sheet->setCellValue('A'.$Ligne,utf8_encode($rowAT['AutresInformations']));
	$sheet->getStyle('A'.$Ligne)->getAlignment()->setWrapText(true);

	$Ligne=$Ligne+3;
	$req="SELECT new_competences_plateforme.Libelle,new_competences_plateforme.Logo 
		FROM new_competences_prestation 
		LEFT JOIN new_competences_plateforme
		ON new_competences_prestation.Id_Plateforme=new_competences_plateforme.Id 
		WHERE new_competences_prestation.Id=".$rowAT['Id_Prestation'];
	$resultPresta=mysqli_query($bdd,$req);
	$nbPresta=mysqli_num_rows($resultPresta);
	if($nbPresta>0){
		$row=mysqli_fetch_array($resultPresta);
		$sheet->setCellValue('F'.$Ligne,utf8_encode($row['Libelle']));
		$sheet->setCellValue('L1',utf8_encode("Plateforme / Site :\n".$row['Libelle']));
		$sheet->getStyle('L1')->getAlignment()->setWrapText(true);
		
		$Logo=$row['Logo'];
		
		if($Logo<>""){
			$objDrawing = new PHPExcel_Worksheet_Drawing();
			$objDrawing->setName('logo');
			$objDrawing->setDescription('PHPExcel logo');
			$objDrawing->setPath('../../Images/Logos/'.$Logo);
			$objDrawing->setWidth(90);
			$objDrawing->setHeight(50);
			$objDrawing->setCoordinates('A1');
			$objDrawing->setOffsetX(10);
			$objDrawing->setOffsetY(8);
			$objDrawing->setWorksheet($sheet);
		}
		
	}
	$sheet->setCellValue('L'.$Ligne,utf8_encode($rowAT['SigleDemandeur']));

	$Ligne=$Ligne+1;
	$sheet->setCellValue('A'.$Ligne,utf8_encode(AfficheDateJJ_MM_AAAA($rowAT['DateCreation'])));
	$sheet->setCellValue('F'.$Ligne,utf8_encode($rowAT['Demandeur']));

	//Enregistrement du fichier excel
	$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

	$chemin = 'AT/D-0250-3 formulaire de declaration des at.xlsx';
	$writer->save($chemin);
}

function ExcelRapport($workbook){
	global $bdd;

	//Enregistrement du fichier excel
	$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

	$chemin = 'Workday/Rapport_ImportWD.xlsx';
	$writer->save($chemin);
}

//Renvoi le nombre d'absences à prendre en compte
function NombreDemandesSansAffectation(){
	global $bdd;
	global $IdPosteResponsableRH;
	global $IdPosteAssistantRH;
	global $IdPosteAideRH;
	global $IdPosteResponsablePlateforme;
	global $IdPosteChefEquipe;
	global $IdPosteCoordinateurEquipe;
	global $IdPosteCoordinateurProjet;
	global $IdPosteResponsableProjet;
	
	$req="SELECT Id
			FROM rh_personne_demandeabsence
			WHERE Suppr=0 
			AND EtatN1<>-1
			AND EtatN2<>-1
			AND Conge=1
			AND EtatRH<>1
			AND Id_Prestation=0
			";

	$result=mysqli_query($bdd,$req);
	$nbResultaDA=mysqli_num_rows($result);
	
	$req="SELECT Id
			FROM rh_personne_rapportastreinte
			WHERE Suppr=0 
			AND EtatN1<>-1
			AND EtatN2<>-1
			AND Id_Prestation=0
			";

	$result=mysqli_query($bdd,$req);
	$nbResultaRA=mysqli_num_rows($result);
	
	return $nbResultaDA+$nbResultaRA;
}

function DateAvant25DuMois($dateAValider,$dateCreation){
	$avant25Mois=1;
	//25 du mois en fonction de la date 
	$date25Mois=date('Y-m-21');
	$moisAnnee=date('Y-m',strtotime($dateAValider." + 0 day"));
	if($moisAnnee==date('Y-m')){
		if($dateCreation>$date25Mois){
			$avant25Mois=0;
		}
	}
	elseif($moisAnnee<date('Y-m')){
		$avant25Mois=0;
	}
	return $avant25Mois;
}
function NombreAlerteHeureSupp($Id_Personne){
	global $bdd;

	$nbTotal=0;
	$mois=date("m");
	$annee=date("Y");
	$dernierJourMois=date('Y-m-d',strtotime(date("Y-m-d",mktime(0,0,0,$mois,1,$annee))." last day of this month"));
	
	$semaine=0;
	$semaineAS=0;
	$nbJoursConsecutifs=0;
	for($laDate=date("Y-m-d",mktime(0,0,0,$mois,1,$annee));$laDate<=$dernierJourMois;$laDate=date('Y-m-d',strtotime($laDate." + 1 day"))){
		$nbHeureJour=NombreHeuresJournee($Id_Personne,$laDate);
		$nbHeureSemaine=0;
		if($semaine<>date('W', strtotime($laDate." + 0  day"))){
			$semaine=date('W', strtotime($laDate." + 0  day"));
			$nbHeureSemaine=NombreHeuresSemaine($Id_Personne,$laDate);
		}
		$prestation=PrestationPoleLibelle_Personne($laDate,$Id_Personne);							
		$ok=1;
		if(substr($prestation,2,5)=="YYYYY" || substr($prestation,2,5)=="CIF00" || substr($prestation,2,5)=="CSS00" || substr($prestation,2,5)=="PAR00" || substr($prestation,2,5)=="SAB00"){$ok=0;}
		if($nbHeureJour>10 && $ok==1){
			$nbTotal++;
		}
		if($nbHeureSemaine>48 && $ok==1){
			$nbTotal++;
		}
		if(ADesHeuresCeJourLa($Id_Personne,$laDate)==1){$nbJoursConsecutifs++;}
		else{$nbJoursConsecutifs=0;}
		if($nbJoursConsecutifs>6 && $semaineAS<>date('W', strtotime($laDate." + 0  day"))  && $ok==1){
			$semaineAS=date('W', strtotime($laDate." + 0  day"));
			$nbTotal++;
		}
		
		if(EstEnVacationCeJour($Id_Personne,$laDate)>0){
			if($nbHeureJour==0  && $ok==1){$nbTotal++;}
		}
	}
	
	return $nbTotal;
}

function NombreFormHorsVacation(){
	global $bdd;
	global $IdPosteResponsableRH;
	global $IdPosteAssistantRH;
	global $IdPosteAideRH;
	$nbForm=0;
	
	$requete2="SELECT form_session_personne.Id,
			form_session_date.DateSession,Id_Personne,DatePriseEnCompteRH,
			Heure_Debut,Heure_Fin,PauseRepas,HeureDebutPause,HeureFinPause,
			(SELECT SUM(Nb_Heures_Jour+Nb_Heures_Nuit) AS Nb 
				FROM rh_personne_hs 
				WHERE rh_personne_hs.Suppr=0 
				AND rh_personne_hs.Id_Personne=form_session_personne.Id_Personne
				AND DateHS=DateSession) AS NbHeuresSupp,
			(SELECT (SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_Prestation) FROM form_besoin WHERE form_besoin.Id=form_session_personne.Id_Besoin) AS Prestation,
			(SELECT (SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=Id_Pole) FROM form_besoin WHERE form_besoin.Id=form_session_personne.Id_Besoin) AS Pole,
			(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_session_personne.Id_Personne) AS Personne,
			(SELECT IF(form_session.Recyclage=1,LibelleRecyclage,Libelle)
				FROM form_formation_langue_infos
				WHERE form_formation_langue_infos.Id_Formation=form_session.Id_Formation
				AND form_formation_langue_infos.Id_Langue=
					(SELECT Id_Langue 
					FROM form_formation_plateforme_parametres 
					WHERE Id_Plateforme=1
					AND Id_Formation=form_session.Id_Formation
					AND Suppr=0 
					LIMIT 1)
				AND Suppr=0) AS Formation ";
	$requete=" FROM
			form_session_date,
			form_session,
			form_session_personne
		WHERE
			form_session_date.Id_Session=form_session.Id
			AND form_session_date.Id_Session=form_session_personne.Id_Session
			AND form_session_date.Suppr=0 
			AND form_session.Suppr=0
			AND form_session.Annule=0 
			AND form_session_personne.Suppr=0
			
			AND YEAR(form_session_date.DateSession)='".date('Y')."'
			AND form_session_personne.Id_Personne IN (
				SELECT DISTINCT rh_personne_mouvement.Id_Personne
				FROM rh_personne_mouvement 
				WHERE rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
				AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."')
				AND rh_personne_mouvement.EtatValidation=1 
				AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_mouvement.Id_Prestation) IN
					(
						SELECT Id_Plateforme 
						FROM new_competences_personne_poste_plateforme
						WHERE Id_Personne=".$_SESSION['Id_Personne']." 
						AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
					)
			) 
			AND form_session_personne.Validation_Inscription=1
			AND form_session_personne.Id_Session=form_session.Id
			AND Presence IN (0,1)
			AND DatePriseEnCompteRH<='0001-01-01'
		  ";
	$result=mysqli_query($bdd,$requete2.$requete);
	$nbResulta=mysqli_num_rows($result);
	
	if($nbResulta>0){
		while($row=mysqli_fetch_array($result))
		{
			$Travail=0;
			$bgcolor="";
			$type="";
			$laCouleur=TravailCeJourDeSemaine($row['DateSession'],$row['Id_Personne']);
			if($laCouleur<>""){
				$Travail=1;
				$type="J";
				$bgcolor="bgcolor='".$laCouleur."'";
			}
			//Vacation particulière
			$VacParticuliere=0;
			$Id_PrestationPole=PrestationPole_Personne($row['DateSession'],$row['Id_Personne']);
			if($Id_PrestationPole<>0){
				$tabPresta=explode("_",$Id_PrestationPole);
				$Id_Presta=$tabPresta[0];
				$Id_Pole=$tabPresta[1];
				
				$req="SELECT Id_Vacation,Id_Prestation,Id_Pole,DateVacation,
					rh_vacation.Nom,rh_vacation.Couleur
					FROM rh_personne_vacation 
					LEFT JOIN rh_vacation
					ON rh_personne_vacation.Id_Vacation=rh_vacation.Id
					WHERE rh_personne_vacation.Suppr=0
					AND rh_personne_vacation.Id_Vacation>0
					AND rh_personne_vacation.Id_Personne=".$row['Id_Personne']."
					AND rh_personne_vacation.DateVacation>='".$row['DateSession']."' 
					AND rh_personne_vacation.DateVacation<='".$row['DateSession']."' 
					";
				$resultVac=mysqli_query($bdd,$req);
				$nbVac=mysqli_num_rows($resultVac);
				if($nbVac>0){
					mysqli_data_seek($resultVac,0);
					while($rowVac=mysqli_fetch_array($resultVac)){
						if($rowVac['Id_Prestation']==$Id_Presta && $rowVac['Id_Pole']==$Id_Pole && $rowVac['DateVacation']==$row['DateSession']){
							$type=$rowVac['Nom'];
							$bgcolor="bgcolor='".$rowVac['Couleur']."'";
							$VacParticuliere=1;
							break;
						}
					}
				}
			}
			//Absences
			if($Travail==1){
				$reqAbs="SELECT rh_absence.Id_Personne_DA,rh_absence.DateDebut,rh_absence.DateFin,Id_TypeAbsenceDefinitif,Id_TypeAbsenceInitial,
					(SELECT rh_typeabsence.CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceInitial) AS TypeAbsenceIni,
					(SELECT rh_typeabsence.CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceDefinitif) AS TypeAbsenceDef
					FROM rh_absence 
					LEFT JOIN rh_personne_demandeabsence 
					ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
					WHERE rh_personne_demandeabsence.Id_Personne=".$row['Id_Personne']." 
					AND rh_absence.DateFin>='".$row['DateSession']."' 
					AND rh_absence.DateDebut<='".$row['DateSession']."' 
					AND rh_personne_demandeabsence.Suppr=0 
					AND rh_absence.Suppr=0  
					AND rh_personne_demandeabsence.Conge=0 
					AND rh_personne_demandeabsence.EtatN1<>-1 
					AND rh_personne_demandeabsence.EtatN2<>-1
					ORDER BY rh_absence.Id DESC, rh_absence.Id_Personne_DA DESC ";
			$resultAbs=mysqli_query($bdd,$reqAbs);
			$nbAbs=mysqli_num_rows($resultAbs);
				if($nbAbs>0){
					mysqli_data_seek($resultAbs,0);
					while($rowAbs=mysqli_fetch_array($resultAbs)){
						if($rowAbs['DateDebut']<=$row['DateSession'] && $rowAbs['DateFin']>=$row['DateSession']){
							$bEtat="validee";
							if($rowAbs['TypeAbsenceDef']<>""){
								$type=$rowAbs['TypeAbsenceDef'];
								if($rowAbs['Id_TypeAbsenceDefinitif']==0){
									$bEtat="absInjustifiee";
									$type="ABS";
								}
							}
							else{
								$type=$rowAbs['TypeAbsenceIni'];
								if($rowAbs['Id_TypeAbsenceInitial']==0){$bEtat="absInjustifiee";$type="ABS";}
							}
							break;
						}
					}
				}
			}
				
			//Congés
			$reqConges="SELECT rh_absence.Id_Personne_DA,rh_absence.DateDebut,rh_absence.DateFin,
					rh_personne_demandeabsence.EtatN1,rh_personne_demandeabsence.EtatN2,rh_personne_demandeabsence.EtatRH,
					(SELECT rh_typeabsence.CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceInitial) AS TypeAbsenceIni,
					(SELECT rh_typeabsence.CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceDefinitif) AS TypeAbsenceDef
					FROM rh_absence 
					LEFT JOIN rh_personne_demandeabsence 
					ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
					WHERE rh_personne_demandeabsence.Id_Personne=".$row['Id_Personne']." 
					AND rh_absence.DateFin>='".$row['DateSession']."' 
					AND rh_absence.DateDebut<='".$row['DateSession']."' 
					AND rh_personne_demandeabsence.Suppr=0 
					AND rh_absence.Suppr=0 
					AND rh_personne_demandeabsence.Annulation=0 
					AND rh_personne_demandeabsence.Conge=1 
					AND rh_personne_demandeabsence.EtatN1<>-1 
					AND rh_personne_demandeabsence.EtatN2<>-1
					ORDER BY rh_absence.Id DESC, rh_absence.Id_Personne_DA DESC ";
		$resultConges=mysqli_query($bdd,$reqConges);
		$nbConges=mysqli_num_rows($resultConges);
		if($nbConges>0){
			mysqli_data_seek($resultConges,0);
			while($rowConges=mysqli_fetch_array($resultConges)){
				if($rowConges['DateDebut']<=$row['DateSession'] && $rowConges['DateFin']>=$row['DateSession']){
					if($rowConges['TypeAbsenceDef']<>""){$type=$rowConges['TypeAbsenceDef'];}
					else{$type=$rowConges['TypeAbsenceIni'];}
					$bEtat="attenteValidation";
					if($rowConges['EtatN2']==1 && $rowConges['EtatRH']==1){$bEtat="validee";}
					break;
				}
			}
		}
		if($VacParticuliere==0){
			$jourFixe=estJour_Fixe($row['DateSession'],$_SESSION['Id_Personne']);
			if($jourFixe<>""){
				$type=$jourFixe;
			}
		}
		
		//Horaires de la personne
		$HeureDebutTravail="00:00:00";
		$HeureFinTravail="00:00:00";
		$tab=HorairesJournee($row['Id_Personne'],$row['DateSession']);
		if(sizeof($tab)>0){
			$HeureDebutTravail=$tab[0];
			$HeureFinTravail=$tab[1];
		}
		
		$nbHeureFormationHorsVac=date('H:i',strtotime($row['DateSession'].' 00:00:00'));
		$nbHeureFormation=date('H:i',strtotime($row['DateSession'].' 00:00:00'));
			if($HeureFinTravail<"03:00:00"){$HeureFinTravail="23:59:00";};
			if($HeureDebutTravail<>"00:00:00" && $HeureFinTravail<>"00:00:00"){
				//Nombre total d'heure de formation
				$hF=strtotime($row['Heure_Fin']);
				$hD=strtotime($row['Heure_Debut']);
				
				$hFP=strtotime($row['HeureFinPause']);
				$hDP=strtotime($row['HeureDebutPause']);
					
				$hFTravail=strtotime($HeureFinTravail);
				$hDTravail=strtotime($HeureDebutTravail);
				
				$valDebut=gmdate("H:i",$hD-$hD);
				$valHPause=gmdate("H:i",$hD-$hD);
				$valFin=gmdate("H:i",$hF-$hF);
				
				//Nombre d'heure hors début vacation 
				if($hFTravail<=$hD || $hDTravail>=$hF){
					$valDebut=gmdate("H:i",$hF-$hD);
					if($row['PauseRepas']==1){
						if($hDP<$hF && $hFP>$hD){
							if($hFP>$hF){$hFP=$hF;}
							if($hDP<$hD){$hDP=$hD;}
							$valPause=gmdate("H:i",$hFP-$hDP);
							$valDebut=gmdate("H:i",strtotime($valDebut)-strtotime($valPause));
						}
					}
				}
				else{
					if($hD<$hDTravail){
						if($hDP<$hDTravail && $row['PauseRepas']==1){
							$valDebut=gmdate("H:i",$hDP-$hD);
							if($hFP<$hDTravail){
								$valHPause=gmdate("H:i",$hDTravail-$hFP);
							}
						}
						else{
							$valDebut=gmdate("H:i",$hDTravail-$hD);
						}
					}
					if($hF>$hFTravail){
						if($hFP>$hFTravail && $row['PauseRepas']==1){
							$valDebut=gmdate("H:i",$hF-$hFP);
							if($hDP>$hFTravail){
								$valHPause=gmdate("H:i",$hDP-$hFTravail);
							}
						}
						else{
							$valFin=gmdate("H:i",$hF-$hFTravail);
						}
					}
				}

				$nbHeureFormHorsVacDebut=intval(date('H',strtotime($valDebut." + 0 hour"))).".".substr((date('i',strtotime($valDebut." + 0 hour"))/0.6),0,2);
				$nbHeureFormHorsVacAvantPause=intval(date('H',strtotime($valHPause." + 0 hour"))).".".substr((date('i',strtotime($valHPause." + 0 hour"))/0.6),0,2);
				$nbHeureFormHorsVacFin=intval(date('H',strtotime($valFin." + 0 hour"))).".".substr((date('i',strtotime($valFin." + 0 hour"))/0.6),0,2);
				
				$nbHeureFormationHorsVac=$nbHeureFormHorsVacDebut+$nbHeureFormHorsVacAvantPause+$nbHeureFormHorsVacFin;
			}
			if($nbHeureFormationHorsVac<>"00:00"){
				$nbForm++;
			}
		}
	}
	return $nbForm;
}

function NombreAnomalieFormation(){
	global $bdd;
	global $IdPosteResponsableRH;
	global $IdPosteAssistantRH;
	global $IdPosteAideRH;
	$mois=date("m");
	$annee=date("Y");
	
	$dernierJourMois=date('Y-m-d',strtotime(date("Y-m-d",mktime(0,0,0,$mois,1,$annee))." last day of this month"));
	
	$nbForm=0;
	$requete2="SELECT DISTINCT form_session_date.DateSession,Id_Personne,
			(SELECT (SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_Prestation) FROM form_besoin WHERE form_besoin.Id=form_session_personne.Id_Besoin) AS Prestation,
			(SELECT (SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=Id_Pole) FROM form_besoin WHERE form_besoin.Id=form_session_personne.Id_Besoin) AS Pole,
			(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_session_personne.Id_Personne) AS Personne ";
	$requete=" FROM
			form_session_date,
			form_session,
			form_session_personne
		WHERE
			form_session_date.Id_Session=form_session.Id
			AND form_session_date.Id_Session=form_session_personne.Id_Session
			AND form_session_date.Suppr=0 
			AND form_session.Suppr=0
			AND form_session.Annule=0 
			AND form_session_date.DateSession>='".$annee."-".$mois."-01'
			AND form_session_date.DateSession<='".$dernierJourMois."'
			";
	$requete.="AND form_session_personne.Suppr=0
			AND form_session_personne.Id_Personne IN (
				SELECT DISTINCT rh_personne_mouvement.Id_Personne
				FROM rh_personne_mouvement 
				WHERE rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
				AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."')
				AND rh_personne_mouvement.EtatValidation=1 
				AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_mouvement.Id_Prestation) IN
					(
						SELECT Id_Plateforme 
						FROM new_competences_personne_poste_plateforme
						WHERE Id_Personne=".$_SESSION['Id_Personne']." 
						AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
					)
			) 
			AND form_session_personne.Validation_Inscription=1
			AND form_session_personne.Id_Session=form_session.Id
			AND Presence IN (0,1)
		  ";
	$result=mysqli_query($bdd,$requete2.$requete);
	$nbResulta=mysqli_num_rows($result);
	
	if($nbResulta>0){
		while($row=mysqli_fetch_array($result))
		{
			$Etat="";
			$Travail=0;
			$bgcolor="";
			$type="";
			$laCouleur=TravailCeJourDeSemaine($row['DateSession'],$row['Id_Personne']);
			if($laCouleur<>""){
				//Horaires de la personne
				$HeureDebutTravail="00:00:00";
				$HeureFinTravail="00:00:00";
				$tab=HorairesJournee($row['Id_Personne'],$row['DateSession']);
				if(sizeof($tab)>0){
					$HeureDebutTravail=$tab[0];
					$HeureFinTravail=$tab[1];
				}
				$reqContrat="SELECT Id,Id_Personne,DateDebut,DateFin,Id_TempsTravail,
							(SELECT EstSalarie FROM rh_typecontrat WHERE rh_typecontrat.Id=rh_personne_contrat.Id_TypeContrat) AS EstSalarie
							FROM rh_personne_contrat
							WHERE Suppr=0
							AND DateDebut<='".$row['DateSession']."'
							AND (DateFin>='".$row['DateSession']."' OR DateFin<='0001-01-01' )
							AND Id_Personne=".$row['Id_Personne']."
							AND TypeDocument IN ('Nouveau','Avenant')
							ORDER BY DateDebut DESC, Id DESC
							";

				$resultC=mysqli_query($bdd,$reqContrat);
				$nb=mysqli_num_rows($resultC);	
				$Id_TypeContrat=0;
				if($nb>0){
					$rowContrat=mysqli_fetch_array($resultC);
					$Id_TypeContrat=$rowContrat['Id_TempsTravail'];
				}
				//Uniquement si non cadre
				if($Id_TypeContrat<>10){
					if($HeureDebutTravail=="00:00:00" && $HeureFinTravail=="00:00:00"){
						$nbForm++;
					}
				}
			}
		}
	}
	return $nbForm;
}
function NombreVacationAPrendreEnCompte(){
	global $bdd;
	global $IdPosteResponsableRH;
	global $IdPosteAssistantRH;
	global $IdPosteAideRH;
	
	$requete="SELECT Id
		FROM
			rh_personne_vacation
		WHERE
			DatePriseEnCompteRH<='0001-01-01'
		AND rh_personne_vacation.Id_Personne IN (
				SELECT DISTINCT rh_personne_mouvement.Id_Personne
				FROM rh_personne_mouvement 
				WHERE rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
				AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."')
				AND rh_personne_mouvement.EtatValidation=1 
				AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_mouvement.Id_Prestation) IN
					(
						SELECT Id_Plateforme 
						FROM new_competences_personne_poste_plateforme
						WHERE Id_Personne=".$_SESSION['Id_Personne']." 
						AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
					)
			)
		AND (
				(Suppr=0 AND YEAR(DateVacation)='".date('Y')."' AND CONCAT(YEAR(DateCreation),'-',IF(MONTH(DateCreation)<10,CONCAT(0,MONTH(DateCreation)),MONTH(DateCreation)),'-',IF(DAY(DateCreation)<10,CONCAT(0,DAY(DateCreation)),DAY(DateCreation)))>CONCAT(YEAR(DateVacation),'-',IF(MONTH(DateVacation)<10,CONCAT(0,MONTH(DateVacation)),MONTH(DateVacation)),'-21'))
			OR  (Suppr=1 AND YEAR(DateSuppr)='".date('Y')."' AND CONCAT(YEAR(DateSuppr),'-',IF(MONTH(DateSuppr)<10,CONCAT(0,MONTH(DateSuppr)),MONTH(DateSuppr)),'-',IF(DAY(DateSuppr)<10,CONCAT(0,DAY(DateSuppr)),DAY(DateSuppr)))>CONCAT(YEAR(DateVacation),'-',IF(MONTH(DateVacation)<10,CONCAT(0,MONTH(DateVacation)),MONTH(DateVacation)),'-21'))
			
			)
		  ";
	
	
	$result=mysqli_query($bdd,$requete);
	$nbResulta=mysqli_num_rows($result);
	return $nbResulta;
}

function NombreVacationJourAlerte(){
	global $bdd;
	global $IdPosteResponsableRH;
	global $IdPosteAssistantRH;
	global $IdPosteAideRH;
	
	$mois=date("m");
	$annee=date("Y");
	$dernierJourMois=date('Y-m-d',strtotime(date("Y-m-d",mktime(0,0,0,$mois,1,$annee))." last day of this month"));
	
	$nb=0;
	$requete = "SELECT DISTINCT new_rh_etatcivil.Id, 
		CONCAT (new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
	FROM new_rh_etatcivil
	WHERE (
		SELECT rh_personne_mouvement.Id_Prestation
		FROM rh_personne_mouvement
		WHERE rh_personne_mouvement.DateDebut<='".$annee."-".$mois."-01'
		AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$dernierJourMois."')
		AND rh_personne_mouvement.EtatValidation=1 
		AND new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne
		AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_mouvement.Id_Prestation) IN
			(
				SELECT Id_Plateforme 
				FROM new_competences_personne_poste_plateforme
				WHERE Id_Personne=".$_SESSION['Id_Personne']." 
				AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
			)
		LIMIT 1
	) NOT IN (87,976,977,978,979,980,981,982,983,984,985,1264,1265,1266,1267)
	AND 
	(
		SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_mouvement.Id_Prestation)
		FROM rh_personne_mouvement
		WHERE rh_personne_mouvement.DateDebut<='".$annee."-".$mois."-01'
		AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$dernierJourMois."')
		AND rh_personne_mouvement.EtatValidation=1 
		AND new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne
		LIMIT 1
	) IN (
		SELECT Id_Plateforme 
		FROM new_competences_personne_poste_plateforme
		WHERE Id_Personne=".$_SESSION['Id_Personne']." 
		AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
	) ";
	
	if($_SESSION['FiltreRHJourAlerte_Personne']<>"0" && $_SESSION['FiltreRHJourAlerte_Personne']<>""){
		$requete.=" AND new_rh_etatcivil.Id=".$_SESSION['FiltreRHJourAlerte_Personne']." ";
	}
	$requete.= " ORDER BY new_rh_etatcivil.Nom ASC, new_rh_etatcivil.Prenom ASC";
	$result=mysqli_query($bdd,$requete);
	$nbResulta=mysqli_num_rows($result);
	if($nbResulta>0){
		while($row=mysqli_fetch_array($result))
		{
			for($laDate=date("Y-m-d",mktime(0,0,0,$mois,1,$annee));$laDate<=$dernierJourMois;$laDate=date('Y-m-d',strtotime($laDate." + 1 day"))){
				$req="SELECT Id FROM rh_jouralerte WHERE Suppr=0 AND DateJour='".$laDate."' ";
				$resultJourAlert=mysqli_query($bdd,$req);
				$nbJourAlerte=mysqli_num_rows($resultJourAlert);
				if($nbJourAlerte>0){
					$Id_Vacation=EstEnVacationCeJour($row['Id'],$laDate);
					if($Id_Vacation>0){
							$prestation=PrestationPoleLibelle_Personne($laDate,$row['Id']);
							
							$ok=1;
							if(substr($prestation,2,5)=="YYYYY" || substr($prestation,2,5)=="CIF00" || substr($prestation,2,5)=="CSS00" || substr($prestation,2,5)=="PAR00" || substr($prestation,2,5)=="SAB00"){$ok=0;}
							if($ok==1){
								$nb++;
							}
					}
				}
			}
		}	
	}
	return $nb;
}

function NombrePetitDeplacementAPrendreEnCompte(){
	global $bdd;
	global $IdPosteResponsableRH;
	global $IdPosteAssistantRH;
	global $IdPosteAideRH;
	
	$requete="SELECT Id
		FROM
			rh_personne_petitdeplacement
		WHERE
			Suppr=0
			AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_petitdeplacement.Id_Prestation) IN 
				(
					SELECT Id_Plateforme 
					FROM new_competences_personne_poste_plateforme
					WHERE Id_Personne=".$_SESSION['Id_Personne']." 
					AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
				) 
			AND rh_personne_petitdeplacement.DatePriseEnCompteRH<='0001-01-01' ";

	$result=mysqli_query($bdd,$requete);
	$nbResulta=mysqli_num_rows($result);
	return $nbResulta;
}
function DateAvant26DuMois($dateAValider,$dateCreation){
	$avant26Mois=1;
	//26 du mois en fonction de la date 
	$date26Mois=date('Y-m-21');
	$moisAnnee=date('Y-m',strtotime($dateAValider." + 0 day"));
	if($moisAnnee==date('Y-m')){
		if($dateCreation>$date26Mois){
			$avant26Mois=0;
		}
	}
	elseif($moisAnnee<date('Y-m')){
		$avant26Mois=0;
	}
	return $avant26Mois;
}

function ADesHeuresCeJourLa($Id_Personne,$DateJour){
	global $bdd;
	$Travail=0;

	//Vérifie si travail ce jour là
	$Couleur=TravailCeJourDeSemaine($DateJour,$Id_Personne);
	
	if($Couleur<>""){
		$Travail=1;		
		//Vérifier si pas des congés ce jour là
		//Liste des congés
		$reqConges="SELECT rh_personne_demandeabsence.Id
					FROM rh_absence 
					LEFT JOIN rh_personne_demandeabsence 
					ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
					WHERE rh_personne_demandeabsence.Id_Personne=".$Id_Personne." 
					AND rh_absence.DateFin>='".$DateJour."' 
					AND rh_absence.DateDebut<='".$DateJour."' 
					AND NbHeureAbsJour=0
					AND NbHeureAbsNuit=0
					AND rh_personne_demandeabsence.Suppr=0 
					AND rh_absence.Suppr=0 
					AND rh_personne_demandeabsence.Annulation=0 
					AND EtatN1<>-1
					AND EtatN2<>-1 ";
		$resultConges=mysqli_query($bdd,$reqConges);
		$nbConges=mysqli_num_rows($resultConges);
		if($nbConges>0){$Travail=0;}
	}
	else{
		//Vérifie si astreintes avec intervention 
		$req="SELECT Id
				FROM rh_personne_rapportastreinte
				WHERE Suppr=0 
				AND Id_Personne=".$Id_Personne."
				AND Intervention=1
				AND ((HeureDebut1<>'00:00:00' OR HeureFin1<>'00:00:00')
					OR (HeureDebut2<>'00:00:00' OR HeureDebut2<>'00:00:00')
					OR (HeureDebut3<>'00:00:00' OR HeureDebut3<>'00:00:00')
					)
				AND IF(DatePriseEnCompte>'0001-01-01',DatePriseEnCompte,DateAstreinte)='".$DateJour."' 
				AND EtatN2=1
				";
		$resultAS=mysqli_query($bdd,$req);
		$nbAS=mysqli_num_rows($resultAS);
		if($nbAS>0){
			$Travail=1;	
		}
		
		//Vérifie si HS ce jour là
		$req="SELECT Id
			FROM rh_personne_hs
			WHERE Suppr=0 
			AND Id_Personne=".$Id_Personne."
			AND IF(DateRH>'0001-01-01',DateRH,DateHS)='".$DateJour."' 
			AND Etat2<>-1
			AND Etat3<>-1
			AND Etat4<>-1
			";
		$resultHS=mysqli_query($bdd,$req);
		$nbHS=mysqli_num_rows($resultHS);
		if($nbHS>0){
			$Travail=1;	
		}
		
	}
	$Id_PrestationPole=PrestationPole_Personne($DateJour,$Id_Personne);
	if($Id_PrestationPole<>0){
		$tabPresta=explode("_",$Id_PrestationPole);
		$Id_Presta=$tabPresta[0];
		$Id_Pole=$tabPresta[1];
		$req="SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=".$Id_Presta;
		$result=mysqli_query($bdd,$req);
		$nb=mysqli_num_rows($result);
		if($nb>0){
			$row=mysqli_fetch_array($result);
			if(estJourFerie($DateJour,$row['Id_Plateforme'])){$Travail=0;}
		}
		$tab=PointagePersonneExceptionnel($Id_Personne,$Id_Presta,$Id_Pole,$DateJour);
		if(sizeof($tab)>0){
			if($tab[0]+$tab[1]+$tab[2]+$tab[3]+$tab[4]+$tab[6]>0){
				$Travail=1;
			}
		}
	}
	
	
	return $Travail;
}

//Renvoi 0 si en contrat sinon 1
function EnContratCeJour($DateJour,$Id_Personne){
	global $bdd;
	$enContrat=0;
	
	if($DateJour<>""){
		$reqContrat="SELECT Id,Id_Personne,DateDebut,DateFin,Id_TempsTravail
					FROM rh_personne_contrat
					WHERE Suppr=0
					AND DateDebut<='".$DateJour."'
					AND (DateFin>='".$DateJour."' OR DateFin<='0001-01-01' )
					AND Id_Personne=".$Id_Personne."
					AND TypeDocument IN ('Nouveau','Avenant')
					";
		$result=mysqli_query($bdd,$reqContrat);
		$nb=mysqli_num_rows($result);
		if($nb>0){	
			$enContrat=1;
		}
	}
	else{
		$enContrat=1;
	}
	return $enContrat;
}

//Renvoi 0 si en contrat sinon 1
function EnContratDansCettePeriode($DateDebut,$DateFin,$Id_Personne){
	global $bdd;
	$enContrat=0;
	
	if($DateDebut<>"" && $DateFin<>""){
		$reqContrat="SELECT Id,Id_Personne,DateDebut,DateFin,Id_TempsTravail
					FROM rh_personne_contrat
					WHERE Suppr=0
					AND DateDebut<='".$DateFin."'
					AND (DateFin>='".$DateDebut."' OR DateFin<='0001-01-01' )
					AND Id_Personne=".$Id_Personne."
					AND TypeDocument IN ('Nouveau','Avenant')
					";
		$result=mysqli_query($bdd,$reqContrat);
		$nb=mysqli_num_rows($result);
		if($nb>0){	
			$enContrat=1;
		}
	}

	return $enContrat;
}

//Renvoi 0 si en contrat sinon 1
function ListeTypeContratDansCettePeriode($DateDebut,$DateFin,$Id_Personne){
	global $bdd;
	$typeContrat="";
	
	if($DateDebut<>"" && $DateFin<>""){
		$reqContrat="SELECT (SELECT Libelle FROM rh_typecontrat WHERE Id=Id_TypeContrat) AS TypeContrat,DateDebut,Id
					FROM rh_personne_contrat
					WHERE Suppr=0
					AND DateDebut<='".$DateFin."'
					AND (DateFin>='".$DateDebut."' OR DateFin<='0001-01-01' )
					AND Id_Personne=".$Id_Personne."
					AND TypeDocument IN ('Nouveau','Avenant')
					ORDER BY DateDebut DESC, Id DESC
					";
		$result=mysqli_query($bdd,$reqContrat);
		$nb=mysqli_num_rows($result);
		if($nb>0){	
			$row=mysqli_fetch_array($result);
			$typeContrat=$row['TypeContrat'];
		}
	}

	return $typeContrat;
}

function NombrePeriodeEssaiLimite(){
	global $bdd;
	global $IdPosteResponsableRH;
	global $IdPosteAssistantRH;
	global $IdPosteAideRH;
	
	$requete="SELECT *
			FROM
			(
				SELECT *
				FROM 
					(SELECT Id,Id_Personne,Id_Metier,Id_TypeContrat,Id_TempsTravail,Titre,DateFinPeriodeEssai,
						(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Personne) AS Personne,
						(SELECT LibelleEN FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS Metier,
						Coeff,DateDebut,DateFin,TypeDocument,TypeCoeff,SalaireBrut,TauxHoraire,(@row_number:=@row_number + 1) AS rnk
					FROM rh_personne_contrat
					WHERE Suppr=0
					AND DateDebut<='".date('Y-m-d')."'
					AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
					AND TypeDocument IN ('Nouveau','Avenant')
					ORDER BY Id_Personne, DateDebut DESC, Id DESC) AS table_contrat 
				GROUP BY Id_Personne
			) AS table_contrat2
			WHERE Id_Personne<>0 
			AND DateFinPeriodeEssai>='".date('Y-m-d')."'
			AND DATE_SUB(DateFinPeriodeEssai, INTERVAL 3 MONTH)<'".date('Y-m-d')."'
			";
	$result=mysqli_query($bdd,$requete);
	$nbResulta=mysqli_num_rows($result);

	return $nbResulta;
}

function NombreContrat18Mois3Semaines(){
	global $bdd;
	global $IdPosteResponsableRH;
	global $IdPosteAssistantRH;
	global $IdPosteAideRH;
	
	$requete="SELECT *
			FROM
			(
				SELECT *,DATE_SUB(DateFin18Mois, INTERVAL 20 DAY) DateFin18Mois3Semaine
				FROM 
					(SELECT Id,Id_Personne,Id_Metier,Id_TypeContrat,Id_TempsTravail,Titre,
						DATE_ADD((SELECT DateDebut18Mois FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Personne), INTERVAL 18 MONTH) DateFin18Mois,
						(SELECT DateDebut18Mois FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Personne) AS DateDebut18Mois,
						(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Personne) AS Personne,
						(SELECT LibelleEN FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS Metier,
						Coeff,DateDebut,DateFin,TypeDocument,TypeCoeff,SalaireBrut,TauxHoraire,(@row_number:=@row_number + 1) AS rnk
					FROM rh_personne_contrat
					WHERE Suppr=0
					AND DateDebut<='".date('Y-m-d')."'
					AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
					AND TypeDocument IN ('Nouveau','Avenant')
					ORDER BY Id_Personne, DateDebut DESC, Id DESC) AS table_contrat 
				GROUP BY Id_Personne
			) AS table_contrat2
			WHERE Id_Personne<>0 
			AND DateFin18Mois3Semaine<='".date('Y-m-d')."'
			";
	$result=mysqli_query($bdd,$requete);
	$nbResulta=mysqli_num_rows($result);

	return $nbResulta;
}

function NombreContratASigner(){
	global $bdd;
	
	$requete="SELECT Id
			FROM rh_personne_contrat
			WHERE Suppr=0
			AND DateSignatureSalarie<='0001-01-01'
			AND TypeDocument IN ('Nouveau','Avenant')
			AND Id_Personne=".$_SESSION['Id_Personne']."
			";
	$result=mysqli_query($bdd,$requete);
	$nbResulta=mysqli_num_rows($result);

	return $nbResulta;
}

function NombreODMASigner(){
	global $bdd;
	
	$requete="SELECT Id
			FROM rh_personne_contrat
			WHERE Suppr=0
			AND DateSignatureSalarie<='0001-01-01'
			AND TypeDocument IN ('ODM')
			AND Id_Personne=".$_SESSION['Id_Personne']."
			";
	$result=mysqli_query($bdd,$requete);
	$nbResulta=mysqli_num_rows($result);

	return $nbResulta;
}

function NombreAbsencesFormationAValider(){
	global $bdd;
	global $IdPosteChefEquipe;
	
	$requete="
			SELECT *
			FROM
			(SELECT form_session_personne.Id,form_session_personne.Id_Personne,form_session_personne.DatePriseEnCompteN1,
			(SELECT form_besoin.Id_Prestation FROM form_besoin WHERE form_besoin.Id=form_session_personne.Id_Besoin) AS Id_Prestation,
			(SELECT form_besoin.Id_Pole FROM form_besoin WHERE form_besoin.Id=form_session_personne.Id_Besoin) AS Id_Pole 

			FROM form_session_personne
			LEFT JOIN form_session
			ON form_session_personne.Id_Session=form_session.Id
			WHERE form_session_personne.Suppr=0 AND form_session_personne.Presence<0) AS TAB
			WHERE  CONCAT(TAB.Id_Prestation,'_',TAB.Id_Pole) IN 
				(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
				FROM new_competences_personne_poste_prestation 
				WHERE Id_Personne=".$_SESSION["Id_Personne"]."
				AND Id_Poste IN (".$IdPosteChefEquipe.") 
				) 
			AND (TAB.DatePriseEnCompteN1<='0001-01-01' AND  
				CONCAT(TAB.Id_Prestation,'_',TAB.Id_Pole) IN 
				(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
				FROM new_competences_personne_poste_prestation 
				WHERE Id_Personne=".$_SESSION["Id_Personne"]."
				AND Id_Poste IN (".$IdPosteChefEquipe.") 
				)
			)";
	$result=mysqli_query($bdd,$requete);
	$nbResulta=mysqli_num_rows($result);

	return $nbResulta;
}

function GenererMailIdentifiantsExtranet($Nom,$Prenom,$Login,$MotDePasse,$DateNaissance,$Email,$Langue)
{
	$Headers='From: "Extranet Daher industriel services DIS"<noreply.extranet@aaa-aero.com>'."\n";
	$Headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";
	
	//Mail pour le Login
	if($Langue=="FR")
	{
		$Objet="Votre compte Extranet Daher industriel services DIS";
		$Message="	<html>
						<head><title>Votre compte Extranet Daher industriel services DIS</title></head>
						<body>
							Bienvenue chez Assistance Aéronautique et Aérospatiale,
							<br><br>
							Daher industriel services DIS a le plaisir de vous informer que votre compte Extranet a été créé.<br>
							Vous trouverez ci-dessous vos informations personnelles :<br>
							Login : ".$Login."<br>
							Nom : ".$Nom."<br>
							Prénom : ".$Prenom."<br>
							Date de naissance : ".AfficheDateJJ_MM_AAAA(TrsfDate_($DateNaissance))."<br>
							<br><br>
							Lien Extranet Daher industriel services DIS : https://extranet.aaa-aero.com <br><br>
							Votre mot de passe sera envoyé dans un courrier séparé.<br><br>
							VEUILLEZ NE PAS RÉPONDRE À CE MESSAGE.
							<br><br>
							Bonne journée.<br>
							Extranet Daher industriel services DIS.
						</body>
					</html>";
	}
	else
	{
		$Objet="Your Extranet Daher industriel services DIS account";
		$Message="	<html>
						<head><title>Your Extranet Daher industriel services DIS account/title></head>
						<body>
							Bienvenue chez Assistance Aéronautique et Aérospatiale,
							<br><br>
							Daher industriel services DIS is pleased to inform you that your Extranet account has been created.<br>
							Here you can find your personal information: <br>
							Login : ".$Login."<br>
							Family Name : ".$Nom."<br>
							Given Name : ".$Prenom."<br>
							Birth date : ".AfficheDateJJ_MM_AAAA(TrsfDate_($DateNaissance))."<br>
							<br><br>
							Extranet Daher industriel services DIS link : https://extranet.aaa-aero.com
							Your password will be sent in a separate mail.<br><br>
							PLEASE DO NOT RESPOND TO THIS MESSAGE.
							<br><br>
							Have a good day.<br>
							Extranet Daher industriel services DIS.
						</body>
					</html>";
	}
		
	if($Email<>"")
	{
		if(mail($Email,$Objet,$Message,$Headers,'-f noreply.extranet@aaa-aero.com')){}
	}
	
	//Mail pour le mot de passe
	if($Langue=="FR")
	{
		$Objet="Votre mot de passe";
		$Message="	<html>
						<head><title>Votre mot de passe</title></head>
						<body>
							Cher utilisateur,
							<br><br>
							Vous devriez avoir reçu un courrier électronique contenant votre nom d'utilisateur pour accéder à L'Extranet Daher industriel services DIS.<br>
							Votre mot de passe est : ".$MotDePasse."<br>
							Si vous avez besoin d'assistance, vous pouvez contacter le service informatique (informatique.aaa@daher.com)
							<br><br>
							VEUILLEZ NE PAS RÉPONDRE À CE MESSAGE.
							<br><br>
							Bonne journée.<br>
							Extranet Daher industriel services DIS.
						</body>
					</html>";
	}
	else
	{
		$Objet="Your password";
		$Message="	<html>
						<head><title>Your password/title></head>
						<body>
							Dear user,
							<br><br>
							You should have received an email containing your username to access the Daher industriel services DIS Extranet.<br>
							Your password is : ".$MotDePasse."<br>
							If you need assistance, you can contact the IT department (informatique.aaa@daher.com)
							<br><br>
							PLEASE DO NOT RESPOND TO THIS MESSAGE.
							<br><br>
							Have a good day.<br>
							Extranet Daher industriel services DIS.
						</body>
					</html>";
	}
		
	if($Email<>"")
	{
		if(mail($Email,$Objet,$Message,$Headers,'-f noreply2.extranet@aaa-aero.com')){}
	}
}

function GenererMailIdentifiantsExtranetRappel($Nom,$Prenom,$Login,$MotDePasse,$DateNaissance,$Email,$Langue)
{
	$Headers='From: "Extranet Daher industriel services DIS"<noreply.extranet@aaa-aero.com>'."\n";
	$Headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";
	
	//Mail pour le Login
	if($Langue=="FR")
	{
		$Objet="Votre compte Extranet Daher industriel services DIS";
		$Message="	<html>
						<head><title>Votre compte Extranet Daher industriel services DIS</title></head>
						<body>
							Vous trouverez ci-dessous vos informations personnelles :<br>
							Login : ".$Login."<br>
							Nom : ".$Nom."<br>
							Prénom : ".$Prenom."<br>
							Date de naissance : ".AfficheDateJJ_MM_AAAA($DateNaissance)."<br>
							<br><br>
							Lien Extranet Daher industriel services DIS : https://extranet.aaa-aero.com <br><br>
							Votre mot de passe sera envoyé dans un courrier séparé.<br><br>
							VEUILLEZ NE PAS RÉPONDRE À CE MESSAGE.
							<br><br>
							Bonne journée.<br>
							Extranet Daher industriel services DIS.
						</body>
					</html>";
	}
	else
	{
		$Objet="Your Extranet Daher industriel services DIS account";
		$Message="	<html>
						<head><title>Your Extranet Daher industriel services DIS account/title></head>
						<body>
							Here you can find your personal information: <br>
							Login : ".$Login."<br>
							Family Name : ".$Nom."<br>
							Given Name : ".$Prenom."<br>
							Birth date : ".AfficheDateJJ_MM_AAAA($DateNaissance)."<br>
							<br><br>
							Extranet Daher industriel services DIS link : https://extranet.aaa-aero.com
							Your password will be sent in a separate mail.<br><br>
							PLEASE DO NOT RESPOND TO THIS MESSAGE.
							<br><br>
							Have a good day.<br>
							Extranet Daher industriel services DIS.
						</body>
					</html>";
	}
		
	if($Email<>"")
	{
		if(mail($Email,$Objet,$Message,$Headers,'-f noreply.extranet@aaa-aero.com')){}
	}
	
	//Mail pour le mot de passe
	if($Langue=="FR")
	{
		$Objet="Votre mot de passe";
		$Message="	<html>
						<head><title>Votre mot de passe</title></head>
						<body>
							Cher utilisateur,
							<br><br>
							Vous devriez avoir reçu un courrier électronique contenant votre nom d'utilisateur pour accéder à L'Extranet Daher industriel services DIS.<br>
							Votre mot de passe est : ".$MotDePasse."<br>
							Si vous avez besoin d'assistance, vous pouvez contacter le service informatique (informatique.aaa@daher.com)
							<br><br>
							VEUILLEZ NE PAS RÉPONDRE À CE MESSAGE.
							<br><br>
							Bonne journée.<br>
							Extranet Daher industriel services DIS.
						</body>
					</html>";
	}
	else
	{
		$Objet="Your password";
		$Message="	<html>
						<head><title>Your password/title></head>
						<body>
							Dear user,
							<br><br>
							You should have received an email containing your username to access the Daher industriel services DIS Extranet.<br>
							Your password is : ".$MotDePasse."<br>
							If you need assistance, you can contact the IT department (informatique.aaa@daher.com)
							<br><br>
							PLEASE DO NOT RESPOND TO THIS MESSAGE.
							<br><br>
							Have a good day.<br>
							Extranet Daher industriel services DIS.
						</body>
					</html>";
	}
		
	if($Email<>"")
	{
		if(mail($Email,$Objet,$Message,$Headers,'-f noreply2.extranet@aaa-aero.com')){}
	}
}

function GenererMailIdentifiantsExtranetV2($Nom,$Prenom,$Login,$MotDePasse,$Email,$Langue)
{
	$Headers='From: "Extranet Daher industriel services DIS"<noreply.extranet@aaa-aero.com>'."\n";
	$Headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";
	
	//Mail pour le Login
	if($Langue=="FR")
	{
		$Objet="Votre compte Extranet Daher industriel services DIS";
		$Message="	<html>
						<head><title>Votre compte Extranet Daher industriel services DIS</title></head>
						<body>
							Bienvenue chez Assistance Aéronautique et Aérospatiale,
							<br><br>
							Daher industriel services DIS a le plaisir de vous informer que votre compte Extranet a été créé.<br>
							Vous trouverez ci-dessous vos informations personnelles :<br>
							Login : ".$Login."<br>
							Nom : ".$Nom."<br>
							Prénom : ".$Prenom."<br>
							<br><br>
							Lien Extranet Daher industriel services DIS : https://extranet.aaa-aero.com <br><br>
							Votre mot de passe sera envoyé dans un courrier séparé.<br><br>
							VEUILLEZ NE PAS RÉPONDRE À CE MESSAGE.
							<br><br>
							Bonne journée.<br>
							Extranet Daher industriel services DIS.
						</body>
					</html>";
	}
	else
	{
		$Objet="Your Extranet Daher industriel services DIS account";
		$Message="	<html>
						<head><title>Your Extranet Daher industriel services DIS account/title></head>
						<body>
							Bienvenue chez Assistance Aéronautique et Aérospatiale,
							<br><br>
							Daher industriel services DIS is pleased to inform you that your Extranet account has been created.<br>
							Here you can find your personal information: <br>
							Login : ".$Login."<br>
							Family Name : ".$Nom."<br>
							Given Name : ".$Prenom."<br>
							<br><br>
							Extranet Daher industriel services DIS link : https://extranet.aaa-aero.com
							Your password will be sent in a separate mail.<br><br>
							PLEASE DO NOT RESPOND TO THIS MESSAGE.
							<br><br>
							Have a good day.<br>
							Extranet Daher industriel services DIS.
						</body>
					</html>";
	}
		
	if($Email<>"")
	{
		if(mail($Email,$Objet,$Message,$Headers,'-f noreply.extranet@aaa-aero.com')){}
	}
	
	//Mail pour le mot de passe
	if($Langue=="FR")
	{
		$Objet="Votre mot de passe";
		$Message="	<html>
						<head><title>Votre mot de passe</title></head>
						<body>
							Cher utilisateur,
							<br><br>
							Vous devriez avoir reçu un courrier électronique contenant votre nom d'utilisateur pour accéder à L'Extranet Daher industriel services DIS.<br>
							Votre mot de passe est : ".$MotDePasse."<br>
							Si vous avez besoin d'assistance, vous pouvez contacter le service informatique (informatique.aaa@daher.com)
							<br><br>
							VEUILLEZ NE PAS RÉPONDRE À CE MESSAGE.
							<br><br>
							Bonne journée.<br>
							Extranet Daher industriel services DIS.
						</body>
					</html>";
	}
	else
	{
		$Objet="Your password";
		$Message="	<html>
						<head><title>Your password/title></head>
						<body>
							Dear user,
							<br><br>
							You should have received an email containing your username to access the Daher industriel services DIS Extranet.<br>
							Your password is : ".$MotDePasse."<br>
							If you need assistance, you can contact the IT department (informatique.aaa@daher.com)
							<br><br>
							PLEASE DO NOT RESPOND TO THIS MESSAGE.
							<br><br>
							Have a good day.<br>
							Extranet Daher industriel services DIS.
						</body>
					</html>";
	}
		
	if($Email<>"")
	{
		if(mail($Email,$Objet,$Message,$Headers,'-f noreply2.extranet@aaa-aero.com')){}
	}
}

/**
 * envoyerMail
 * 
 * cette fonction a ete cree suite a la copie du code de l\'article suivant : https://openclassrooms.com/courses/e-mail-envoyer-un-e-mail-en-php
 * Attention a l\'utilisation des pieces jointes. Il faut fournir un tableau de tableau avec les champs nommés suivants
 *  chemin, nom, attachement
 *  
 *   voici un exemple :
 *   
 *   $PJ = array();
 *   
 *   $pj1 = array('chemin' => 'C:\\Users\\ASCHRICKE\\Pictures\\Pas trié\\', 'nom' => 'd76885b37c7969c3fecf861887987efc.jpg', 'MIME-Type' => 'image/jpeg', 'attachement' => encoderFichier('C:\\Users\\ASCHRICKE\\Pictures\\Pas trié\\d76885b37c7969c3fecf861887987efc.jpg'));
 *   $pj2 = array('chemin' => 'C:\\Users\\ASCHRICKE\\Pictures\\Pas trié\\', 'nom' => 'Dassault-Mirage-2000N.jpg', 'MIME-Type' => 'image/jpeg', 'attachement' => encoderFichier('C:\\Users\\ASCHRICKE\\Pictures\\Pas trié\\Dassault-Mirage-2000N.jpg'));
 *   $pj3 = array('chemin' => 'C:\\Users\\ASCHRICKE\\Pictures\\Pas trié\\', 'nom' => 'Fighter_Airplane_440908.jpg', 'MIME-Type' => 'image/jpeg', 'attachement' => encoderFichier('C:\\Users\\ASCHRICKE\\Pictures\\Pas trié\\Fighter_Airplane_440908.jpg'));
 *   
 *   array_push($PJ, $pj1);
 *   array_push($PJ, $pj2);
 *   array_push($PJ, $pj3);
 *   
 * 
 * @param string $destinataire Le ou les destinataires du mail (s\épar\és par une virgule
 * @param string $sujet Le sujet du mail
 * @param string $message_txt Le corps du mail en format texte plat
 * @param string $message_html Le corps du mail en format html
 * @param array $PJ Tableau de tableau structur\é contenant les informations des pi\èces jointes
 * 
 * @author Weaponsb
 */
function envoyerMailRH($destinataire, $sujet, $message_txt, $message_html, $PJ = Array()) {
	
    // regarde si il y a des Pieces Jointes
    if(count($PJ) > 0) {    
    	//$mail = 'pfauge@aaa-aero.com'; // Déclaration de l'adresse de destination.
    	if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $destinataire)) // On filtre les serveurs qui présentent des bogues.
    	{
    		$passage_ligne = "\r\n";
    	}
    	else
    	{
    		$passage_ligne = "\n";
    	}
    	//=====Déclaration des messages au format texte et au format HTML.
    	//$message_txt = "Salut à tous, voici un e-mail envoyé par un script PHP.";
    	//$message_html = "<html><head></head><body><b>Salut à tous</b>, voici un e-mail envoyé par un <i>script PHP</i>.</body></html>";
    	//==========
    	
    	$attachements = array();
    
    	//=====Création de la boundary.
    	$boundary = "-----=".md5(rand());
    	$boundary_alt = "-----=".md5(rand());
    	//==========
    	
    	//=====Définition du sujet.
    	//$sujet = "Hey mon ami !";
    	//=========
    	
    	//=====Création du header de l'e-mail.
    	$header = "From: \"Extranet Daher industriel services DIS\"<noreply.extranet@aaa-aero.com>".$passage_ligne;
    //	$header.= "Reply-to: \"La meme adresse\" <aschricke@aaa-aero.com>".$passage_ligne;
    	$header.= "MIME-Version: 1.0".$passage_ligne;
    	$header.= "Content-Type: multipart/mixed;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
    	//==========
    	
    	//=====Création du message.
    	$message = $passage_ligne."--".$boundary.$passage_ligne;
    	$message.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary_alt\"".$passage_ligne;
    	$message.= $passage_ligne."--".$boundary_alt.$passage_ligne;
    	//=====Ajout du message au format texte.
    	$message.= "Content-Type: text/plain; charset=\"ISO-8859-1\"".$passage_ligne;
    	$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
    	$message.= $passage_ligne.$message_txt.$passage_ligne;
    	//==========
    	
    	$message.= $passage_ligne."--".$boundary_alt.$passage_ligne;
    	
    	//=====Ajout du message au format HTML.
    	$message.= "Content-Type: text/html; charset=\"ISO-8859-1\"".$passage_ligne;
    	$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
    	$message.= $passage_ligne.$message_html.$passage_ligne;
    	//==========
    	
    	//=====On ferme la boundary alternative.
    	$message.= $passage_ligne."--".$boundary_alt."--".$passage_ligne;
    	//==========
    	
    	foreach($PJ as $current_PJ_infos) {
    	//for ($curseur = 0; $curseur < count($attachements); $curseur++ ) {
    		$message.= $passage_ligne."--".$boundary.$passage_ligne;
    		
    		//=====Ajout de la pièce jointe.
    		$message.= "Content-Type: image/jpeg; name=\"".$current_PJ_infos['nom']."\"".$passage_ligne;
    		$message.= "Content-Transfer-Encoding: base64".$passage_ligne;
    		$message.= "Content-Disposition: attachment; filename=\"".$current_PJ_infos['nom']."\"".$passage_ligne;
    		$message.= $passage_ligne.$current_PJ_infos['attachement'].$passage_ligne.$passage_ligne;
    	}
    	$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
    	
    	//==========
    }else {
        //Headers
        $header='From: "Extranet Daher industriel services DIS"<noreply.extranet@aaa-aero.com>'." \n";
        $header.='Content-Type: text/html; charset="iso-8859-1"'." \n";
        
        //Message html
        $message = $message_html;
    }
	
	
	//=====Envoi de l'e-mail.
	if ($destinataire <> "")
		return mail($destinataire,$sujet,$message,$header,'-f noreply.extranet@aaa-aero.com');
	else
		return false;
	
	//==========
}

function NombreHeuresTotalJournee($Id_Personne,$DateJour,$Id_Prestation = 0){
	global $bdd;
	$NbHeures=0;
	$tabDate = explode('-', $DateJour);
	$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
	$jourSemaine = date('w', $timestamp);
	$Id_Vacation=0;
	//Récupérer les valeurs sur la prestaton
	$req="SELECT Id_Prestation,Id_Pole 
			FROM rh_personne_mouvement
			LEFT JOIN new_competences_prestation
			ON rh_personne_mouvement.Id_Prestation=new_competences_prestation.Id
			WHERE rh_personne_mouvement.DateDebut<='".$DateJour."'
			AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$DateJour."')
			AND rh_personne_mouvement.EtatValidation=1 
			AND rh_personne_mouvement.Suppr=0
			AND rh_personne_mouvement.Id_Personne=".$Id_Personne."
			";
	$result=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($result);
	
	if($nbResulta>0){
		$rowPresta=mysqli_fetch_array($result);
		
		if($Id_Prestation==0 || $Id_Prestation==$rowPresta['Id_Prestation']){
			//Récupérer l'Id_vacation de cette journée en fonction
			$Couleur=TravailCeJourDeSemaine($DateJour,$Id_Personne);
			if($Couleur<>""){
				$Id_Vacation=IdVacationCeJourDeSemaine($DateJour,$Id_Personne);
				$Id_Contenu=estJour_Fixe_Id($DateJour,$Id_Personne);
				if($Id_Contenu<>""){$Id_Vacation=$Id_Contenu;}
				
				//Vérifier si la personne n'a pas une vacation particulière ce jour là 
				$Id_Contenu=VacationPersonne($DateJour,$Id_Personne,$rowPresta['Id_Prestation'],$rowPresta['Id_Pole']);
				if($Id_Contenu>0){$Id_Vacation=$Id_Contenu;}
				//Vérifier si la personne n'est pas absente ou en congé ce jour là 
				$reqAbs="SELECT rh_absence.Id,rh_absence.NbHeureAbsJour,rh_absence.NbHeureAbsNuit,
							(SELECT Ponderation FROM rh_typeabsence WHERE rh_typeabsence.Id=(IF(rh_absence.Id_TypeAbsenceDefinitif>0,rh_absence.Id_TypeAbsenceDefinitif,rh_absence.Id_TypeAbsenceInitial))) AS Ponderation
							FROM rh_absence 
							LEFT JOIN rh_personne_demandeabsence 
							ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
							WHERE rh_personne_demandeabsence.Id_Personne=".$Id_Personne." 
							AND rh_absence.DateFin>='".$DateJour."' 
							AND rh_absence.DateDebut<='".$DateJour."' 
							AND rh_personne_demandeabsence.Suppr=0 
							AND rh_absence.Suppr=0  
							AND EtatN1<>-1
							AND EtatN2<>-1
							AND (EtatN2=1 OR DatePriseEnCompteRH>'0001-01-01') ";
				$resultAbs=mysqli_query($bdd,$reqAbs);
				$nbAbs=mysqli_num_rows($resultAbs);
				$Ponderation=0;
				if($nbAbs>0){
					$Id_Vacation=0;
					
					$rowAbsence=mysqli_fetch_array($resultAbs);
					$Ponderation=$rowAbsence['Ponderation'];
				}
				
				if($Id_Vacation>0){
					//Calcul du nombre d'heure de la journée en fonction de sa vacation, de son contrat et de sa prestation 
					$tabContrat=PointagePersonneContrat($DateJour,$Id_Personne,$Id_Vacation,$jourSemaine);
					if(sizeof($tabContrat)>0){
						$NbHeures=$tabContrat[0]+$tabContrat[1]+$tabContrat[2];
						$Id_Contrat=IdContrat($Id_Personne,$DateJour);
						if($Id_Contrat>0){
							if(Id_TypeContrat($Id_Contrat)==10){
								$NbHeures=7;
							}
						}
					}
					else{
						$tab=PointagePrestationVacation($rowPresta['Id_Prestation'],$rowPresta['Id_Pole'],$Id_Vacation,$jourSemaine,$DateJour);
						if(sizeof($tab)>0){
							$NbHeures=$tab[0]+$tab[1]+$tab[2]+$tab[4];
						}
					}
					
				}
				else{
					$NbHeures=$Ponderation;
				}
				
				//Ajouter les Heures supplémentaires 
				$req="SELECT SUM(Nb_Heures_Jour) AS NbJour,SUM(Nb_Heures_Nuit) AS NbNuit
						FROM rh_personne_hs
						WHERE Suppr=0 
						AND Id_Personne=".$Id_Personne." 
						AND DateHS='".$DateJour."' 
						AND Etat4=1
						";
				$resultHS=mysqli_query($bdd,$req);
				$nbHS=mysqli_num_rows($resultHS);
				if($nbHS>0){
					$rowHS=mysqli_fetch_array($resultHS);
					$NbHeures=$NbHeures+$rowHS['NbJour']+$rowHS['NbNuit'];
				}
				
				//Ne pas compter les astreintes 
			}
		}
	}
	return $NbHeures;
}

function NombreHeuresTotalJourneeRepartition($Id_Personne,$DateJour,$Id_Prestation = 0){
	global $bdd;
	$NbHeures=0;
	$tabDate = explode('-', $DateJour);
	$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
	$jourSemaine = date('w', $timestamp);
	$Id_Vacation=0;
	//Récupérer les valeurs sur la prestaton
	$req="SELECT Id_Prestation,Id_Pole 
			FROM rh_personne_mouvement
			LEFT JOIN new_competences_prestation
			ON rh_personne_mouvement.Id_Prestation=new_competences_prestation.Id
			WHERE rh_personne_mouvement.DateDebut<='".$DateJour."'
			AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$DateJour."')
			AND rh_personne_mouvement.EtatValidation=1 
			AND rh_personne_mouvement.Suppr=0
			AND rh_personne_mouvement.Id_Personne=".$Id_Personne."
			";
	$result=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($result);
	
	if($nbResulta>0){
		$rowPresta=mysqli_fetch_array($result);
		
		if($Id_Prestation==0 || $Id_Prestation==$rowPresta['Id_Prestation']){
			//Récupérer l'Id_vacation de cette journée en fonction
			$Couleur=TravailCeJourDeSemaine($DateJour,$Id_Personne);
			if($Couleur<>""){
				$Id_Vacation=IdVacationCeJourDeSemaine($DateJour,$Id_Personne);
				$Id_Contenu=estJour_Fixe_Id($DateJour,$Id_Personne);
				if($Id_Contenu<>""){$Id_Vacation=$Id_Contenu;}
				
				//Vérifier si la personne n'a pas une vacation particulière ce jour là 
				$Id_Contenu=VacationPersonne($DateJour,$Id_Personne,$rowPresta['Id_Prestation'],$rowPresta['Id_Pole']);
				if($Id_Contenu>0){$Id_Vacation=$Id_Contenu;}
				//Vérifier si la personne n'est pas absente ou en congé ce jour là 
				$reqAbs="SELECT rh_absence.Id,rh_absence.NbHeureAbsJour,rh_absence.NbHeureAbsNuit,
							(SELECT Ponderation FROM rh_typeabsence WHERE rh_typeabsence.Id=(IF(rh_absence.Id_TypeAbsenceDefinitif>0,rh_absence.Id_TypeAbsenceDefinitif,rh_absence.Id_TypeAbsenceInitial))) AS Ponderation
							FROM rh_absence 
							LEFT JOIN rh_personne_demandeabsence 
							ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
							WHERE rh_personne_demandeabsence.Id_Personne=".$Id_Personne." 
							AND rh_absence.DateFin>='".$DateJour."' 
							AND rh_absence.DateDebut<='".$DateJour."' 
							AND rh_personne_demandeabsence.Suppr=0 
							AND rh_absence.Suppr=0  
							AND EtatN1<>-1
							AND EtatN2<>-1
							AND (EtatN2=1 OR DatePriseEnCompteRH>'0001-01-01') ";
				$resultAbs=mysqli_query($bdd,$reqAbs);
				$nbAbs=mysqli_num_rows($resultAbs);
				$Ponderation=0;
				if($nbAbs>0){
					$Id_Vacation=0;
					
					$rowAbsence=mysqli_fetch_array($resultAbs);
					$Ponderation=$rowAbsence['Ponderation'];
				}
				
				if($Id_Vacation>0 && $Id_Vacation<>14){
					//Calcul du nombre d'heure de la journée en fonction de sa vacation, de son contrat et de sa prestation 
					$tabContrat=PointagePersonneContrat($DateJour,$Id_Personne,$Id_Vacation,$jourSemaine);
					if(sizeof($tabContrat)>0){
						$NbHeures=$tabContrat[0]+$tabContrat[1]+$tabContrat[2];
						$Id_Contrat=IdContrat($Id_Personne,$DateJour);
						if($Id_Contrat>0){
							if(Id_TypeContrat($Id_Contrat)==10){
								$NbHeures=7;
							}
						}
					}
					else{
						$tab=PointagePrestationVacation($rowPresta['Id_Prestation'],$rowPresta['Id_Pole'],$Id_Vacation,$jourSemaine,$DateJour);
						if(sizeof($tab)>0){
							$NbHeures=$tab[0]+$tab[1]+$tab[2]+$tab[4];
						}
					}
					
				}
				elseif($Id_Vacation==14){
					$NbHeures=7.4;
				}
				else{
					$NbHeures=$Ponderation;
				}
				
				//Ajouter les Heures supplémentaires 
				$req="SELECT SUM(Nb_Heures_Jour) AS NbJour,SUM(Nb_Heures_Nuit) AS NbNuit
						FROM rh_personne_hs
						WHERE Suppr=0 
						AND Id_Personne=".$Id_Personne." 
						AND DateHS='".$DateJour."' 
						AND Etat4=1
						";
				$resultHS=mysqli_query($bdd,$req);
				$nbHS=mysqli_num_rows($resultHS);
				if($nbHS>0){
					$rowHS=mysqli_fetch_array($resultHS);
					$NbHeures=$NbHeures+$rowHS['NbJour']+$rowHS['NbNuit'];
				}
				
				//Ne pas compter les astreintes 
			}
		}
	}
	return $NbHeures;
}

function NombreHeuresTotalJourneeRepartition2($Id_Personne,$DateJour,$Id_Prestation = 0){
	global $bdd;
	$NbHeures=0;
	$tabDate = explode('-', $DateJour);
	$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
	$jourSemaine = date('w', $timestamp);
	$Id_Vacation=0;
	//Récupérer les valeurs sur la prestaton
	$req="SELECT Id_Prestation,Id_Pole 
			FROM rh_personne_mouvement
			LEFT JOIN new_competences_prestation
			ON rh_personne_mouvement.Id_Prestation=new_competences_prestation.Id
			WHERE rh_personne_mouvement.DateDebut<='".$DateJour."'
			AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$DateJour."')
			AND rh_personne_mouvement.EtatValidation=1 
			AND rh_personne_mouvement.Suppr=0
			AND rh_personne_mouvement.Id_Personne=".$Id_Personne."
			";
	$result=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($result);
	
	if($nbResulta>0){
		$rowPresta=mysqli_fetch_array($result);
		
		if($Id_Prestation==0 || $Id_Prestation==$rowPresta['Id_Prestation']){
			//Récupérer l'Id_vacation de cette journée en fonction
			$Couleur=TravailCeJourDeSemaine($DateJour,$Id_Personne);
			if($Couleur<>""){
				$Id_Vacation=IdVacationCeJourDeSemaine($DateJour,$Id_Personne);
				$Id_Contenu=estJour_Fixe_Id($DateJour,$Id_Personne);
				if($Id_Contenu<>""){$Id_Vacation=$Id_Contenu;}
				
				//Vérifier si la personne n'a pas une vacation particulière ce jour là 
				$Id_Contenu=VacationPersonne($DateJour,$Id_Personne,$rowPresta['Id_Prestation'],$rowPresta['Id_Pole']);
				if($Id_Contenu>0){$Id_Vacation=$Id_Contenu;}
				//Vérifier si la personne n'est pas absente ou en congé ce jour là 
				$reqAbs="SELECT rh_absence.Id,rh_absence.NbHeureAbsJour,rh_absence.NbHeureAbsNuit,
							(SELECT Ponderation FROM rh_typeabsence WHERE rh_typeabsence.Id=(IF(rh_absence.Id_TypeAbsenceDefinitif>0,rh_absence.Id_TypeAbsenceDefinitif,rh_absence.Id_TypeAbsenceInitial))) AS Ponderation
							FROM rh_absence 
							LEFT JOIN rh_personne_demandeabsence 
							ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
							WHERE rh_personne_demandeabsence.Id_Personne=".$Id_Personne." 
							AND rh_absence.DateFin>='".$DateJour."' 
							AND rh_absence.DateDebut<='".$DateJour."' 
							AND rh_personne_demandeabsence.Suppr=0 
							AND rh_absence.Suppr=0  
							AND EtatN1<>-1
							AND EtatN2<>-1
							AND (EtatN2=1 OR DatePriseEnCompteRH>'0001-01-01') ";
				$resultAbs=mysqli_query($bdd,$reqAbs);
				$nbAbs=mysqli_num_rows($resultAbs);
				$Ponderation=0;
				if($nbAbs>0){
					$Id_Vacation=0;
					
					$rowAbsence=mysqli_fetch_array($resultAbs);
					$Ponderation=$rowAbsence['Ponderation'];
				}
				
				if($Id_Vacation>0 && $Id_Vacation<>14){
					//Calcul du nombre d'heure de la journée en fonction de sa vacation, de son contrat et de sa prestation 
					$tabContrat=PointagePersonneContrat($DateJour,$Id_Personne,$Id_Vacation,$jourSemaine);
					if(sizeof($tabContrat)>0){
						$NbHeures=$tabContrat[0]+$tabContrat[1]+$tabContrat[2];
						$Id_Contrat=IdContrat($Id_Personne,$DateJour);
						if($Id_Contrat>0){
							if(Id_TypeContrat($Id_Contrat)==10){
								$NbHeures=7;
							}
						}
					}
					else{
						$tab=PointagePrestationVacation($rowPresta['Id_Prestation'],$rowPresta['Id_Pole'],$Id_Vacation,$jourSemaine,$DateJour);
						if(sizeof($tab)>0){
							$NbHeures=$tab[0]+$tab[1]+$tab[2]+$tab[4];
						}
					}
					
				}
				elseif($Id_Vacation==14){
					$NbHeures=7.4;
				}
				
				//Ajouter les Heures supplémentaires 
				$req="SELECT SUM(Nb_Heures_Jour) AS NbJour,SUM(Nb_Heures_Nuit) AS NbNuit
						FROM rh_personne_hs
						WHERE Suppr=0 
						AND Id_Personne=".$Id_Personne." 
						AND DateHS='".$DateJour."' 
						AND Etat4=1
						";
				$resultHS=mysqli_query($bdd,$req);
				$nbHS=mysqli_num_rows($resultHS);
				if($nbHS>0){
					$rowHS=mysqli_fetch_array($resultHS);
					$NbHeures=$NbHeures+$rowHS['NbJour']+$rowHS['NbNuit'];
				}
				
				//Ne pas compter les astreintes 
			}
		}
	}
	return $NbHeures;
}

function NombreHeuresFormation($Id_Personne,$DateJour,$Id_Prestation = 0){
	global $bdd;
	$NbHeures=0;
	$tabDate = explode('-', $DateJour);
	$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
	$jourSemaine = date('w', $timestamp);
	$Id_Vacation=0;
	$nbHeureFormationVac=date('H:i',strtotime($DateJour.' 00:00:00'));
	$nbHeureFormation=date('H:i',strtotime($DateJour.' 00:00:00'));
								
	//Récupérer les valeurs sur la prestaton
	$req="SELECT Id_Prestation,Id_Pole 
			FROM rh_personne_mouvement
			LEFT JOIN new_competences_prestation
			ON rh_personne_mouvement.Id_Prestation=new_competences_prestation.Id
			WHERE rh_personne_mouvement.DateDebut<='".$DateJour."'
			AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$DateJour."')
			AND rh_personne_mouvement.EtatValidation=1 
			AND rh_personne_mouvement.Suppr=0
			AND rh_personne_mouvement.Id_Personne=".$Id_Personne."
			";
	$result=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($result);
	
	if($nbResulta>0){
		$rowPresta=mysqli_fetch_array($result);
		
		if($Id_Prestation==0 || $Id_Prestation==$rowPresta['Id_Prestation']){
			//Récupérer l'Id_vacation de cette journée en fonction
			$Couleur=TravailCeJourDeSemaine($DateJour,$Id_Personne);
			if($Couleur<>""){
				$Id_Vacation=IdVacationCeJourDeSemaine($DateJour,$Id_Personne);
				$Id_Contenu=estJour_Fixe_Id($DateJour,$Id_Personne);
				if($Id_Contenu<>""){$Id_Vacation=$Id_Contenu;}
				
				//Vérifier si la personne n'a pas une vacation particulière ce jour là 
				$Id_Contenu=VacationPersonne($DateJour,$Id_Personne,$rowPresta['Id_Prestation'],$rowPresta['Id_Pole']);
				if($Id_Contenu>0){$Id_Vacation=$Id_Contenu;}
				//Vérifier si la personne n'est pas absente ou en congé ce jour là 
				$reqAbs="SELECT rh_absence.Id
							FROM rh_absence 
							LEFT JOIN rh_personne_demandeabsence 
							ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
							WHERE rh_personne_demandeabsence.Id_Personne=".$Id_Personne." 
							AND rh_absence.DateFin>='".$DateJour."' 
							AND rh_absence.DateDebut<='".$DateJour."' 
							AND rh_personne_demandeabsence.Suppr=0 
							AND rh_absence.Suppr=0  
							AND EtatN1<>-1
							AND EtatN2<>-1
							AND (EtatN2=1 OR DatePriseEnCompteRH>'0001-01-01') ";
				$resultAbs=mysqli_query($bdd,$reqAbs);
				$nbAbs=mysqli_num_rows($resultAbs);
				$Ponderation=0;
				if($nbAbs>0){
					$Id_Vacation=0;
				}
				
				if($Id_Vacation>0){
					//Horaires de la personne
					$HeureDebutTravail="00:00:00";
					$HeureFinTravail="00:00:00";

					$tab=HorairesJournee($Id_Personne,$DateJour);
					if(sizeof($tab)>0){
						$HeureDebutTravail=$tab[0];
						$HeureFinTravail=$tab[1];
					}
				
					
					//Formation dans l'outil formation 
					$req="  SELECT
								form_session_date.DateSession,form_session_personne.Id_Personne,
								Heure_Debut,Heure_Fin,PauseRepas,HeureDebutPause,HeureFinPause
							FROM
								form_session_date 
								LEFT JOIN form_session ON form_session_date.Id_Session=form_session.Id
								LEFT JOIN form_session_personne ON form_session_personne.Id_Session=form_session.Id
							WHERE
								form_session_date.Suppr=0 
								AND form_session.Suppr=0
								AND form_session.Annule=0 
								AND form_session_date.DateSession='".$DateJour."'
								AND form_session_personne.Suppr=0
								AND form_session_personne.Id_Personne=".$Id_Personne." 
								AND form_session_personne.Validation_Inscription=1
								AND form_session_personne.Id_Session=form_session.Id
								AND Presence IN (0,1)
								 ";
							
					$resultSession=mysqli_query($bdd,$req);
					$nbSession=mysqli_num_rows($resultSession);
					
					$tab_Formation= array();
					if($nbSession>0){
						mysqli_data_seek($resultSession,0);
						while($rowForm=mysqli_fetch_array($resultSession)){
								$tab_Formation[] = array(
									'DateSession' => $rowForm['DateSession'], 
									'Id_Personne' => $rowForm['Id_Personne'], 
									'Heure_Debut' => $rowForm['Heure_Debut'], 
									'Heure_Fin' => $rowForm['Heure_Fin'], 
									'PauseRepas' => $rowForm['PauseRepas'], 
									'HeureDebutPause' => $rowForm['HeureDebutPause'],
									'HeureFinPause' => $rowForm['HeureFinPause']
								);
						}
					}
					
					if($HeureDebutTravail<>"00:00:00" && $HeureFinTravail<>"00:00:00"){
						//Formation 
						if(sizeof($tab_Formation)>0){
							$bTrouve=0;
							foreach($tab_Formation as $rowForm){
								
								if($rowForm['DateSession']==$DateJour){
									
									//Nombre total d'heure de formation
									$hF=strtotime($rowForm['Heure_Fin']);
									$hD=strtotime($rowForm['Heure_Debut']);
									$val=gmdate("H:i",$hF-$hD);
									$bTrouve=1;
									if($rowForm['PauseRepas']==1){
										$hFP=strtotime($rowForm['HeureFinPause']);
										$hDP=strtotime($rowForm['HeureDebutPause']);
										if($hDP<$hF && $hFP>$hD){
											if($hFP>$hF){$hFP=$hF;}
											if($hDP<$hD){$hDP=$hD;}
											$valPause=gmdate("H:i",$hFP-$hDP);
											$val=gmdate("H:i",strtotime($val)-strtotime($valPause));
										}
									}
									
									$nbHeureFormation=date('H:i',strtotime($nbHeureFormation." ".str_replace(":"," hour ",$val)." minute"));

									//Nombre d'heure pendant la vacation 
									if($HeureFinTravail<"03:00:00"){$HeureFinTravail="23:59:00";}
									$hFTravail=strtotime($HeureFinTravail);
									$hDTravail=strtotime($HeureDebutTravail);
									if($hDTravail>$hD || $hFTravail<$hF){
										if($hFTravail<$hF){$hF=$hFTravail;}
										if($hDTravail>$hD){$hD=$hDTravail;}
									}
									$val=gmdate("H:i",$hF-$hD);
									
									if($hDTravail>$hF || $hFTravail<$hD){
										$hF=0;
										$hD=0;
										$val=0;
									}
									
									if($hD<>0 && $hF<>0){
										if($rowForm['PauseRepas']==1){
											$hFP=strtotime($rowForm['HeureFinPause']);
											$hDP=strtotime($rowForm['HeureDebutPause']);
											if($hDP<$hF && $hFP>$hD){
												if($hFP>$hF){$hFP=$hF;}
												if($hDP<$hD){$hDP=$hD;}
												$valPause=gmdate("H:i",$hFP-$hDP);
												$val=gmdate("H:i",strtotime($val)-strtotime($valPause));
											}
										}
									}
									$nbHeureFormationVac=date('H:i',strtotime($nbHeureFormationVac." ".str_replace(":"," hour ",$val)." minute"));
								}
							}
						}
					}
					
					
				}
			}
		}
	}
	return $nbHeureFormationVac;
}


function EnCPouRTTCeJourSurCettePresta($Id_Personne,$DateJour,$Id_Prestation){
	global $bdd;
	$EnCPouRTT=0;
	$tabDate = explode('-', $DateJour);
	$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
	$jourSemaine = date('w', $timestamp);
	$Id_Vacation=0;
	//Récupérer les valeurs sur la prestaton
	$req="SELECT Id_Prestation,Id_Pole 
			FROM rh_personne_mouvement
			LEFT JOIN new_competences_prestation
			ON rh_personne_mouvement.Id_Prestation=new_competences_prestation.Id
			WHERE rh_personne_mouvement.DateDebut<='".$DateJour."'
			AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$DateJour."')
			AND rh_personne_mouvement.EtatValidation=1 
			AND rh_personne_mouvement.Id_Prestation=".$Id_Prestation."
			AND rh_personne_mouvement.Suppr=0
			AND rh_personne_mouvement.Id_Personne=".$Id_Personne."
			";
	$result=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($result);
	
	if($nbResulta>0){
		//Vérifier si la personne n'est pas absente ou en congé ce jour là 
		$reqAbs="SELECT rh_absence.Id
					FROM rh_absence 
					LEFT JOIN rh_personne_demandeabsence 
					ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
					WHERE rh_personne_demandeabsence.Id_Personne=".$Id_Personne." 
					AND rh_absence.DateFin>='".$DateJour."' 
					AND rh_absence.DateDebut<='".$DateJour."' 
					AND rh_personne_demandeabsence.Suppr=0 
					AND rh_absence.Suppr=0  
					AND IF(Id_TypeAbsenceDefinitif>0,Id_TypeAbsenceDefinitif,Id_TypeAbsenceInitial) IN (3,4,5,7,55)
					AND EtatN1<>-1
					AND EtatN2<>-1
					AND (EtatN2=1 OR DatePriseEnCompteRH>'0001-01-01') ";
		$resultAbs=mysqli_query($bdd,$reqAbs);
		$nbAbs=mysqli_num_rows($resultAbs);
		if($nbAbs>0){
			$EnCPouRTT=1;
		}
	}
	return $EnCPouRTT;
}

function EnGardeEnfantCeJourSurCettePresta($Id_Personne,$DateJour,$Id_Prestation){
	global $bdd;
	$EnGardeEnfant=0;
	$tabDate = explode('-', $DateJour);
	$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
	$jourSemaine = date('w', $timestamp);
	$Id_Vacation=0;
	//Récupérer les valeurs sur la prestaton
	$req="SELECT Id_Prestation,Id_Pole 
			FROM rh_personne_mouvement
			LEFT JOIN new_competences_prestation
			ON rh_personne_mouvement.Id_Prestation=new_competences_prestation.Id
			WHERE rh_personne_mouvement.DateDebut<='".$DateJour."'
			AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$DateJour."')
			AND rh_personne_mouvement.EtatValidation=1 
			AND rh_personne_mouvement.Id_Prestation=".$Id_Prestation."
			AND rh_personne_mouvement.Suppr=0
			AND rh_personne_mouvement.Id_Personne=".$Id_Personne."
			";
	$result=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($result);
	
	if($nbResulta>0){
		//Vérifier si la personne n'est pas absente ou en congé ce jour là 
		$reqAbs="SELECT rh_absence.Id
					FROM rh_absence 
					LEFT JOIN rh_personne_demandeabsence 
					ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
					WHERE rh_personne_demandeabsence.Id_Personne=".$Id_Personne." 
					AND rh_absence.DateFin>='".$DateJour."' 
					AND rh_absence.DateDebut<='".$DateJour."' 
					AND rh_personne_demandeabsence.Suppr=0 
					AND rh_absence.Suppr=0  
					AND IF(Id_TypeAbsenceDefinitif>0,Id_TypeAbsenceDefinitif,Id_TypeAbsenceInitial) IN (26,30,60,61,65)
					AND EtatN1<>-1
					AND EtatN2<>-1
					AND (EtatN2=1 OR DatePriseEnCompteRH>'0001-01-01') ";
		$resultAbs=mysqli_query($bdd,$reqAbs);
		$nbAbs=mysqli_num_rows($resultAbs);
		if($nbAbs>0){
			$EnGardeEnfant=1;
		}
	}
	return $EnGardeEnfant;
}

function EnMaladieInf3CeJourSurCettePresta($Id_Personne,$DateJour,$Id_Prestation){
	global $bdd;
	$EnMaladie=0;
	$tabDate = explode('-', $DateJour);
	$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
	$jourSemaine = date('w', $timestamp);
	$Id_Vacation=0;
	$nbJourAM=0;
	//Récupérer les valeurs sur la prestaton
	$req="SELECT Id_Prestation,Id_Pole 
			FROM rh_personne_mouvement
			LEFT JOIN new_competences_prestation
			ON rh_personne_mouvement.Id_Prestation=new_competences_prestation.Id
			WHERE rh_personne_mouvement.DateDebut<='".$DateJour."'
			AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$DateJour."')
			AND rh_personne_mouvement.EtatValidation=1 
			AND rh_personne_mouvement.Id_Prestation=".$Id_Prestation."
			AND rh_personne_mouvement.Suppr=0
			AND rh_personne_mouvement.Id_Personne=".$Id_Personne."
			";
	$result=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($result);
	
	if($nbResulta>0){
		//Vérifier si la personne n'est pas absente ou en congé ce jour là 
		$reqAbs="SELECT rh_absence.Id
					FROM rh_absence 
					LEFT JOIN rh_personne_demandeabsence 
					ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
					WHERE rh_personne_demandeabsence.Id_Personne=".$Id_Personne." 
					AND rh_absence.DateFin>='".$DateJour."' 
					AND rh_absence.DateDebut<='".$DateJour."' 
					AND rh_personne_demandeabsence.Suppr=0 
					AND rh_absence.Suppr=0  
					AND IF(Id_TypeAbsenceDefinitif>0,Id_TypeAbsenceDefinitif,Id_TypeAbsenceInitial) IN (1,77,23)
					AND EtatN1<>-1
					AND EtatN2<>-1
					AND (EtatN2=1 OR DatePriseEnCompteRH>'0001-01-01') ";
		$resultAbs=mysqli_query($bdd,$reqAbs);
		$nbAbs=mysqli_num_rows($resultAbs);
		if($nbAbs>0){
			//Vérifier si jour travaillé
			$JourTravail=TravailCeJourDeSemaine($DateJour,$Id_Personne);
			if($JourTravail<>""){
				$EnMaladie=1;
			}
		}
	}
	
	if($EnMaladie==1){
		$dateATester=$DateJour;
		$bValide=1;
		$nbJourAM=0;
		
		while($bValide==1){
			
			$req="SELECT rh_personne_demandeabsence.Id 
			FROM rh_personne_demandeabsence
			WHERE rh_personne_demandeabsence.Suppr=0 
			AND Id_Personne=".$Id_Personne."
			AND EtatN1<>-1
			AND EtatN2<>-1
			AND (EtatN2=1 OR DatePriseEnCompteRH>'0001-01-01')
			AND (SELECT COUNT(Id)
				FROM rh_absence 
				WHERE Suppr=0
				AND Id_Personne_DA=rh_personne_demandeabsence.Id
				AND IF(Id_TypeAbsenceDefinitif>0,Id_TypeAbsenceDefinitif,Id_TypeAbsenceInitial) IN (1,77,23)
				AND DateDebut<='".$dateATester."'
				AND DateFin>='".$dateATester."'
			)>0 ";
			$resultTest=mysqli_query($bdd,$req);
			$nbTest=mysqli_num_rows($resultTest);
			
			if($nbTest>0){
				$JourTravail=TravailCeJourDeSemaine($dateATester,$Id_Personne);
				if($JourTravail<>""){
					$nbJourAM++;
				}
				$dateATester=date('Y-m-d',strtotime($dateATester." - 1 day"));
			}
			else{
				$bValide=0;
			}
		}
	}
	if($nbJourAM>3){$EnMaladie=0;}
	return $EnMaladie;
}

function EnMaladieSup3CeJourSurCettePresta($Id_Personne,$DateJour,$Id_Prestation){
	global $bdd;
	$EnMaladie=0;
	$tabDate = explode('-', $DateJour);
	$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
	$jourSemaine = date('w', $timestamp);
	$Id_Vacation=0;
	$nbJourAM=0;
	//Récupérer les valeurs sur la prestaton
	$req="SELECT Id_Prestation,Id_Pole 
			FROM rh_personne_mouvement
			LEFT JOIN new_competences_prestation
			ON rh_personne_mouvement.Id_Prestation=new_competences_prestation.Id
			WHERE rh_personne_mouvement.DateDebut<='".$DateJour."'
			AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$DateJour."')
			AND rh_personne_mouvement.EtatValidation=1 
			AND rh_personne_mouvement.Id_Prestation=".$Id_Prestation."
			AND rh_personne_mouvement.Suppr=0
			AND rh_personne_mouvement.Id_Personne=".$Id_Personne."
			";
	$result=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($result);
	
	if($nbResulta>0){
		//Vérifier si la personne n'est pas absente ou en congé ce jour là 
		$reqAbs="SELECT rh_absence.Id
					FROM rh_absence 
					LEFT JOIN rh_personne_demandeabsence 
					ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
					WHERE rh_personne_demandeabsence.Id_Personne=".$Id_Personne." 
					AND rh_absence.DateFin>='".$DateJour."' 
					AND rh_absence.DateDebut<='".$DateJour."' 
					AND rh_personne_demandeabsence.Suppr=0 
					AND rh_absence.Suppr=0  
					AND IF(Id_TypeAbsenceDefinitif>0,Id_TypeAbsenceDefinitif,Id_TypeAbsenceInitial) IN (1,77,23)
					AND EtatN1<>-1
					AND EtatN2<>-1
					AND (EtatN2=1 OR DatePriseEnCompteRH>'0001-01-01') ";
		$resultAbs=mysqli_query($bdd,$reqAbs);
		$nbAbs=mysqli_num_rows($resultAbs);
		if($nbAbs>0){
			$JourTravail=TravailCeJourDeSemaine($DateJour,$Id_Personne);
			if($JourTravail<>""){
				$EnMaladie=1;
			}
		}
	}
	
	if($EnMaladie==1){
		$dateATester=$DateJour;
		$bValide=1;
		$nbJourAM=0;
		
		while($bValide==1){
			
			$req="SELECT rh_personne_demandeabsence.Id 
			FROM rh_personne_demandeabsence
			WHERE rh_personne_demandeabsence.Suppr=0 
			AND Id_Personne=".$Id_Personne."
			AND EtatN1<>-1
			AND EtatN2<>-1
			AND (EtatN2=1 OR DatePriseEnCompteRH>'0001-01-01')
			AND (SELECT COUNT(Id)
				FROM rh_absence 
				WHERE Suppr=0
				AND Id_Personne_DA=rh_personne_demandeabsence.Id
				AND IF(Id_TypeAbsenceDefinitif>0,Id_TypeAbsenceDefinitif,Id_TypeAbsenceInitial) IN (1,77,23)
				AND DateDebut<='".$dateATester."'
				AND DateFin>='".$dateATester."'
			)>0 ";
			$resultTest=mysqli_query($bdd,$req);
			$nbTest=mysqli_num_rows($resultTest);
			
			if($nbTest>0){
				$JourTravail=TravailCeJourDeSemaine($dateATester,$Id_Personne);
				if($JourTravail<>""){
					$nbJourAM++;
				}
				$dateATester=date('Y-m-d',strtotime($dateATester." - 1 day"));
			}
			else{
				$bValide=0;
			}
		}
	}
	if($nbJourAM<=3){$EnMaladie=0;}
	return $EnMaladie;
}

function EnCOVIDCeJourSurCettePresta($Id_Personne,$DateJour,$Id_Prestation){
	global $bdd;
	$EnCOVID=0;
	$tabDate = explode('-', $DateJour);
	$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
	$jourSemaine = date('w', $timestamp);
	$Id_Vacation=0;
	//Récupérer les valeurs sur la prestaton
	$req="SELECT Id_Prestation,Id_Pole 
			FROM rh_personne_mouvement
			LEFT JOIN new_competences_prestation
			ON rh_personne_mouvement.Id_Prestation=new_competences_prestation.Id
			WHERE rh_personne_mouvement.DateDebut<='".$DateJour."'
			AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$DateJour."')
			AND rh_personne_mouvement.EtatValidation=1 
			AND rh_personne_mouvement.Id_Prestation=".$Id_Prestation."
			AND rh_personne_mouvement.Suppr=0
			AND rh_personne_mouvement.Id_Personne=".$Id_Personne."
			";
	$result=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($result);
	
	if($nbResulta>0){
		//Vérifier si la personne n'est pas absente ou en congé ce jour là 
		$reqAbs="SELECT rh_absence.Id
					FROM rh_absence 
					LEFT JOIN rh_personne_demandeabsence 
					ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
					WHERE rh_personne_demandeabsence.Id_Personne=".$Id_Personne." 
					AND rh_absence.DateFin>='".$DateJour."' 
					AND rh_absence.DateDebut<='".$DateJour."' 
					AND rh_personne_demandeabsence.Suppr=0 
					AND rh_absence.Suppr=0  
					AND IF(Id_TypeAbsenceDefinitif>0,Id_TypeAbsenceDefinitif,Id_TypeAbsenceInitial) IN (62,63)
					AND EtatN1<>-1
					AND EtatN2<>-1
					AND (EtatN2=1 OR DatePriseEnCompteRH>'0001-01-01') ";
		$resultAbs=mysqli_query($bdd,$reqAbs);
		$nbAbs=mysqli_num_rows($resultAbs);
		if($nbAbs>0){
			$EnCOVID=1;
		}
	}
	return $EnCOVID;
}

function EnCOVIDTTCeJourSurCettePresta($Id_Personne,$DateJour,$Id_Prestation){
	global $bdd;
	$EnCOVIDTT=0;
	$tabDate = explode('-', $DateJour);
	$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
	$jourSemaine = date('w', $timestamp);
	$Id_Vacation=0;
	//Récupérer les valeurs sur la prestaton
	$req="SELECT Id_Prestation,Id_Pole 
			FROM rh_personne_mouvement
			LEFT JOIN new_competences_prestation
			ON rh_personne_mouvement.Id_Prestation=new_competences_prestation.Id
			WHERE rh_personne_mouvement.DateDebut<='".$DateJour."'
			AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$DateJour."')
			AND rh_personne_mouvement.EtatValidation=1 
			AND rh_personne_mouvement.Id_Prestation=".$Id_Prestation."
			AND rh_personne_mouvement.Suppr=0
			AND rh_personne_mouvement.Id_Personne=".$Id_Personne."
			";
	$result=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($result);
	
	if($nbResulta>0){
		//Vérifier si la personne n'est pas absente ou en congé ce jour là 
		$reqAbs="SELECT rh_absence.Id
					FROM rh_absence 
					LEFT JOIN rh_personne_demandeabsence 
					ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
					WHERE rh_personne_demandeabsence.Id_Personne=".$Id_Personne." 
					AND rh_absence.DateFin>='".$DateJour."' 
					AND rh_absence.DateDebut<='".$DateJour."' 
					AND rh_personne_demandeabsence.Suppr=0 
					AND rh_absence.Suppr=0  
					AND IF(Id_TypeAbsenceDefinitif>0,Id_TypeAbsenceDefinitif,Id_TypeAbsenceInitial) IN (64)
					AND EtatN1<>-1
					AND EtatN2<>-1
					AND (EtatN2=1 OR DatePriseEnCompteRH>'0001-01-01') ";
		$resultAbs=mysqli_query($bdd,$reqAbs);
		$nbAbs=mysqli_num_rows($resultAbs);
		if($nbAbs>0){
			$EnCOVIDTT=1;
		}
	}
	return $EnCOVIDTT;
}

function EnAbsenceCeJourSurCettePresta($Id_Personne,$DateJour,$Id_Prestation){
	global $bdd;
	$EnAbs=0;
	$tabDate = explode('-', $DateJour);
	$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
	$jourSemaine = date('w', $timestamp);
	$Id_Vacation=0;
	$nbJourAM=0;
	//Récupérer les valeurs sur la prestaton
	$req="SELECT Id_Prestation,Id_Pole 
			FROM rh_personne_mouvement
			LEFT JOIN new_competences_prestation
			ON rh_personne_mouvement.Id_Prestation=new_competences_prestation.Id
			WHERE rh_personne_mouvement.DateDebut<='".$DateJour."'
			AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$DateJour."')
			AND rh_personne_mouvement.EtatValidation=1 
			AND rh_personne_mouvement.Id_Prestation=".$Id_Prestation."
			AND rh_personne_mouvement.Suppr=0
			AND rh_personne_mouvement.Id_Personne=".$Id_Personne."
			";
	$result=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($result);
	
	if($nbResulta>0){
		//Vérifier si la personne n'est pas absente ou en congé ce jour là 
		$reqAbs="SELECT rh_absence.Id
					FROM rh_absence 
					LEFT JOIN rh_personne_demandeabsence 
					ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
					WHERE rh_personne_demandeabsence.Id_Personne=".$Id_Personne." 
					AND rh_absence.DateFin>='".$DateJour."' 
					AND rh_absence.DateDebut<='".$DateJour."' 
					AND rh_personne_demandeabsence.Suppr=0 
					AND rh_absence.Suppr=0  
					AND IF(Id_TypeAbsenceDefinitif>0,Id_TypeAbsenceDefinitif,Id_TypeAbsenceInitial) IN (0)
					AND EtatN1<>-1
					AND EtatN2<>-1
					AND (EtatN2=1 OR DatePriseEnCompteRH>'0001-01-01') ";
		$resultAbs=mysqli_query($bdd,$reqAbs);
		$nbAbs=mysqli_num_rows($resultAbs);
		if($nbAbs>0){
			$EnAbs=1;
		}
	}
	
	return $EnAbs;
}

function EnEquipeCeJourSurCettePresta($Id_Personne,$DateJour,$Id_Prestation){
	global $bdd;
	$NbHeures=0;
	$tabDate = explode('-', $DateJour);
	$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
	$jourSemaine = date('w', $timestamp);
	$EnEquipe=0;
	$Vacation="";
	$Id_Vacation=0;
	//Récupérer les valeurs sur la prestaton
	$req="SELECT Id_Prestation,Id_Pole 
			FROM rh_personne_mouvement
			LEFT JOIN new_competences_prestation
			ON rh_personne_mouvement.Id_Prestation=new_competences_prestation.Id
			WHERE rh_personne_mouvement.DateDebut<='".$DateJour."'
			AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$DateJour."')
			AND rh_personne_mouvement.EtatValidation=1 
			AND rh_personne_mouvement.Id_Prestation=".$Id_Prestation."
			AND rh_personne_mouvement.Suppr=0
			AND rh_personne_mouvement.Id_Personne=".$Id_Personne."
			";
	$result=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($result);
	
	if($nbResulta>0){
		$rowPresta=mysqli_fetch_array($result);
		
		//Récupérer l'Id_vacation de cette journée en fonction
		$Couleur=TravailCeJourDeSemaine($DateJour,$Id_Personne);
		if($Couleur<>""){
			$Id_Vacation=IdVacationCeJourDeSemaine($DateJour,$Id_Personne);
			$Id_Contenu=estJour_Fixe_Id($DateJour,$Id_Personne);
			if($Id_Contenu<>""){$Id_Vacation=0;}
			
			//Vérifier si la personne n'a pas une vacation particulière ce jour là 
			$Id_Contenu=VacationPersonne($DateJour,$Id_Personne,$rowPresta['Id_Prestation'],$rowPresta['Id_Pole']);
			if($Id_Contenu>0){$Id_Vacation=$Id_Contenu;}
			//Vérifier si la personne n'est pas absente ou en congé ce jour là 
			$reqAbs="SELECT rh_absence.Id
						FROM rh_absence 
						LEFT JOIN rh_personne_demandeabsence 
						ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
						WHERE rh_personne_demandeabsence.Id_Personne=".$Id_Personne." 
						AND rh_absence.DateFin>='".$DateJour."' 
						AND rh_absence.DateDebut<='".$DateJour."' 
						AND rh_personne_demandeabsence.Suppr=0 
						AND rh_absence.Suppr=0  
						AND EtatN1<>-1
						AND EtatN2<>-1
						AND (EtatN2=1 OR DatePriseEnCompteRH>'0001-01-01') ";
			$resultAbs=mysqli_query($bdd,$reqAbs);
			$nbAbs=mysqli_num_rows($resultAbs);
			$Ponderation=0;
			if($nbAbs>0){
				$Id_Vacation=0;
			}
			
			if($Id_Vacation>0){
				$req="SELECT Nom FROM rh_vacation WHERE Id=".$Id_Vacation;
				$resultVac=mysqli_query($bdd,$req);
				$nbVac=mysqli_num_rows($resultVac);
				if($nbVac>0){
					$rowVac=mysqli_fetch_array($resultVac);
					$Vacation=$rowVac['Nom'];
				}
				
			}
			else{
				$Vacation="";
			}
		}
	}
	if(substr($Vacation,0,2)=="EJ" || substr($Vacation,0,2)=="ES" || substr($Vacation,0,2)=="EN"){$EnEquipe=1;}
	return $EnEquipe;
}

function TypeVacationJournee($Id_Personne,$DateJour,$Id_Prestation = 0){
	global $bdd;
	$NbHeures=0;
	$tabDate = explode('-', $DateJour);
	$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
	$jourSemaine = date('w', $timestamp);
	$Vacation="";
	$Id_Vacation=0;
	//Récupérer les valeurs sur la prestaton
	$req="SELECT Id_Prestation,Id_Pole 
			FROM rh_personne_mouvement
			LEFT JOIN new_competences_prestation
			ON rh_personne_mouvement.Id_Prestation=new_competences_prestation.Id
			WHERE rh_personne_mouvement.DateDebut<='".$DateJour."'
			AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$DateJour."')
			AND rh_personne_mouvement.EtatValidation=1 
			AND rh_personne_mouvement.Suppr=0
			AND rh_personne_mouvement.Id_Personne=".$Id_Personne."
			";
	$result=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($result);
	
	if($nbResulta>0){
		$rowPresta=mysqli_fetch_array($result);
		
		if($Id_Prestation==0 || $Id_Prestation==$rowPresta['Id_Prestation']){
			//Récupérer l'Id_vacation de cette journée en fonction
			$Couleur=TravailCeJourDeSemaine($DateJour,$Id_Personne);
			if($Couleur<>""){
				$Id_Vacation=IdVacationCeJourDeSemaine($DateJour,$Id_Personne);
				$Id_Contenu=estJour_Fixe_Id($DateJour,$Id_Personne);
				if($Id_Contenu<>""){$Id_Vacation=0;}
				
				//Vérifier si la personne n'a pas une vacation particulière ce jour là 
				$Id_Contenu=VacationPersonne($DateJour,$Id_Personne,$rowPresta['Id_Prestation'],$rowPresta['Id_Pole']);
				if($Id_Contenu>0){$Id_Vacation=$Id_Contenu;}
				//Vérifier si la personne n'est pas absente ou en congé ce jour là 
				$reqAbs="SELECT rh_absence.Id
							FROM rh_absence 
							LEFT JOIN rh_personne_demandeabsence 
							ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
							WHERE rh_personne_demandeabsence.Id_Personne=".$Id_Personne." 
							AND rh_absence.DateFin>='".$DateJour."' 
							AND rh_absence.DateDebut<='".$DateJour."' 
							AND rh_personne_demandeabsence.Suppr=0 
							AND rh_absence.Suppr=0  
							AND EtatN1<>-1
							AND EtatN2<>-1
							AND (EtatN2=1 OR DatePriseEnCompteRH>'0001-01-01') ";
				$resultAbs=mysqli_query($bdd,$reqAbs);
				$nbAbs=mysqli_num_rows($resultAbs);
				$Ponderation=0;
				if($nbAbs>0){
					$Id_Vacation=0;
				}
				
				if($Id_Vacation>0){
					$req="SELECT Nom FROM rh_vacation WHERE Id=".$Id_Vacation;
					$resultVac=mysqli_query($bdd,$req);
					$nbVac=mysqli_num_rows($resultVac);
					if($nbVac>0){
						$rowVac=mysqli_fetch_array($resultVac);
						$Vacation=$rowVac['Nom'];
					}
					
				}
				else{
					$Vacation="";
				}
			}
		}
	}
	return $Vacation;
}

function EnVacationCeJour($Id_Personne,$DateJour,$Id_Prestation){
	global $bdd;
	$NbHeures=0;
	$tabDate = explode('-', $DateJour);
	$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
	$jourSemaine = date('w', $timestamp);
	$Vacation="";
	$Id_Vacation=0;
	//Récupérer les valeurs sur la prestaton
	$req="SELECT Id_Prestation,Id_Pole 
			FROM rh_personne_mouvement
			LEFT JOIN new_competences_prestation
			ON rh_personne_mouvement.Id_Prestation=new_competences_prestation.Id
			WHERE rh_personne_mouvement.DateDebut<='".$DateJour."'
			AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$DateJour."')
			AND rh_personne_mouvement.EtatValidation=1 
			AND rh_personne_mouvement.Id_Prestation=".$Id_Prestation."
			AND rh_personne_mouvement.Suppr=0
			AND rh_personne_mouvement.Id_Personne=".$Id_Personne."
			";
	$result=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($result);
	
	if($nbResulta>0){
		$rowPresta=mysqli_fetch_array($result);
		
		if($Id_Prestation==0 || $Id_Prestation==$rowPresta['Id_Prestation']){
			//Récupérer l'Id_vacation de cette journée en fonction
			$Couleur=TravailCeJourDeSemaine($DateJour,$Id_Personne);
			if($Couleur<>""){
				$Id_Vacation=IdVacationCeJourDeSemaine($DateJour,$Id_Personne);
				$Id_Contenu=estJour_Fixe_Id($DateJour,$Id_Personne);
				if($Id_Contenu<>""){$Id_Vacation=0;}
				
				//Vérifier si la personne n'a pas une vacation particulière ce jour là 
				$Id_Contenu=VacationPersonne($DateJour,$Id_Personne,$rowPresta['Id_Prestation'],$rowPresta['Id_Pole']);
				if($Id_Contenu>0){$Id_Vacation=$Id_Contenu;}
				//Vérifier si la personne n'est pas absente ou en congé ce jour là 
				$reqAbs="SELECT rh_absence.Id
							FROM rh_absence 
							LEFT JOIN rh_personne_demandeabsence 
							ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
							WHERE rh_personne_demandeabsence.Id_Personne=".$Id_Personne." 
							AND rh_absence.DateFin>='".$DateJour."' 
							AND rh_absence.DateDebut<='".$DateJour."' 
							AND rh_personne_demandeabsence.Suppr=0 
							AND rh_absence.Suppr=0  
							AND EtatN1<>-1
							AND EtatN2<>-1
							AND (EtatN2=1 OR DatePriseEnCompteRH>'0001-01-01') ";
				$resultAbs=mysqli_query($bdd,$reqAbs);
				$nbAbs=mysqli_num_rows($resultAbs);
				$Ponderation=0;
				if($nbAbs>0){
					$Id_Vacation=0;
				}
				
				if($Id_Vacation>0){
					$req="SELECT Nom FROM rh_vacation WHERE Id=".$Id_Vacation;
					$resultVac=mysqli_query($bdd,$req);
					$nbVac=mysqli_num_rows($resultVac);
					if($nbVac>0){
						$rowVac=mysqli_fetch_array($resultVac);
						$Vacation=$rowVac['Nom'];
					}
					
				}
				else{
					$Vacation="";
				}
			}
		}
	}
	if($Id_Vacation>0){$Id_Vacation=1;}
	return $Id_Vacation;
}

function MontantIndemniteCeJour($Id_Personne,$DateJour,$Id_Prestation){
	global $bdd;
	$NbHeures=0;
	$tabDate = explode('-', $DateJour);
	$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
	$jourSemaine = date('w', $timestamp);
	$Vacation="";
	$Id_Vacation=0;
	$Montant=0;
	//Récupérer les valeurs sur la prestaton
	$req="SELECT Id_Prestation,Id_Pole 
			FROM rh_personne_mouvement
			LEFT JOIN new_competences_prestation
			ON rh_personne_mouvement.Id_Prestation=new_competences_prestation.Id
			WHERE rh_personne_mouvement.DateDebut<='".$DateJour."'
			AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$DateJour."')
			AND rh_personne_mouvement.EtatValidation=1 
			AND rh_personne_mouvement.Id_Prestation=".$Id_Prestation."
			AND rh_personne_mouvement.Suppr=0
			AND rh_personne_mouvement.Id_Personne=".$Id_Personne."
			";
	$result=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($result);
	
	if($nbResulta>0){
		$rowPresta=mysqli_fetch_array($result);
		
		if($Id_Prestation==0 || $Id_Prestation==$rowPresta['Id_Prestation']){
			//Récupérer l'Id_vacation de cette journée en fonction
			$Couleur=TravailCeJourDeSemaine($DateJour,$Id_Personne);
			if($Couleur<>""){
				$Id_Vacation=IdVacationCeJourDeSemaine($DateJour,$Id_Personne);
				$Id_Contenu=estJour_Fixe_Id($DateJour,$Id_Personne);
				if($Id_Contenu<>""){$Id_Vacation=0;}
				
				//Vérifier si la personne n'a pas une vacation particulière ce jour là 
				$Id_Contenu=VacationPersonne($DateJour,$Id_Personne,$rowPresta['Id_Prestation'],$rowPresta['Id_Pole']);
				if($Id_Contenu>0){$Id_Vacation=$Id_Contenu;}
				//Vérifier si la personne n'est pas absente ou en congé ce jour là 
				$reqAbs="SELECT rh_absence.Id
							FROM rh_absence 
							LEFT JOIN rh_personne_demandeabsence 
							ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
							WHERE rh_personne_demandeabsence.Id_Personne=".$Id_Personne." 
							AND rh_absence.DateFin>='".$DateJour."' 
							AND rh_absence.DateDebut<='".$DateJour."' 
							AND rh_personne_demandeabsence.Suppr=0 
							AND rh_absence.Suppr=0  
							AND EtatN1<>-1
							AND EtatN2<>-1
							AND (EtatN2=1 OR DatePriseEnCompteRH>'0001-01-01') ";
				$resultAbs=mysqli_query($bdd,$reqAbs);
				$nbAbs=mysqli_num_rows($resultAbs);
				$Ponderation=0;
				if($nbAbs>0){
					$Id_Vacation=0;
				}
				
				if($Id_Vacation>0){
					$req="SELECT Nom FROM rh_vacation WHERE Id=".$Id_Vacation;
					$resultVac=mysqli_query($bdd,$req);
					$nbVac=mysqli_num_rows($resultVac);
					if($nbVac>0){
						$rowVac=mysqli_fetch_array($resultVac);
						$Vacation=$rowVac['Nom'];
					}
					
				}
				else{
					$Vacation="";
				}
			}
		}
	}
	if($Id_Vacation>0){
		$Montant=6+MontantDeplacementCeJour($DateJour,$Id_Personne);
		
	}
	return $Montant;
}

//Vérifie si la personne est salarié à un jour donnée 
function MontantDeplacementCeJour($DateJour,$Id_Personne){
	global $bdd;
	$Montant=0;
	
	$tabDate = explode('-', $DateJour);
	$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
	$jourSemaine = date('w', $timestamp);
	
	$reqContrat="SELECT Id,Id_Personne,DateDebut,DateFin,Id_TempsTravail,
				MontantIPD
				FROM rh_personne_contrat
				WHERE Suppr=0
				AND DateDebut<='".$DateJour."'
				AND (DateFin>='".$DateJour."' OR DateFin<='0001-01-01' )
				AND Id_Personne=".$Id_Personne."
				AND TypeDocument IN ('ODM')
				ORDER BY DateDebut DESC, Id DESC
				";
	$result=mysqli_query($bdd,$reqContrat);
	$nb=mysqli_num_rows($result);
	
	if($nb>0){
		$rowContrat=mysqli_fetch_array($result);
		$Montant=$rowContrat['MontantIPD'];
	}
	return $Montant;
}

function NombreHeuresTotalJourneeCout($Id_Personne,$DateJour,$Id_Prestation = 0){
	global $bdd;
	$NbHeures=0;
	$tabDate = explode('-', $DateJour);
	$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
	$jourSemaine = date('w', $timestamp);
	$Id_Vacation=0;
	//Récupérer les valeurs sur la prestaton
	$req="SELECT Id_Prestation,Id_Pole 
			FROM rh_personne_mouvement
			LEFT JOIN new_competences_prestation
			ON rh_personne_mouvement.Id_Prestation=new_competences_prestation.Id
			WHERE rh_personne_mouvement.DateDebut<='".$DateJour."'
			AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$DateJour."')
			AND rh_personne_mouvement.EtatValidation=1 
			AND rh_personne_mouvement.Suppr=0
			AND rh_personne_mouvement.Id_Personne=".$Id_Personne."
			";
	$result=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($result);
	
	if($nbResulta>0){
		$rowPresta=mysqli_fetch_array($result);
		
		if($Id_Prestation==0 || $Id_Prestation==$rowPresta['Id_Prestation']){
			//Récupérer l'Id_vacation de cette journée en fonction
			$Couleur=TravailCeJourDeSemaine($DateJour,$Id_Personne);
			if($Couleur<>""){
				$Id_Vacation=IdVacationCeJourDeSemaine($DateJour,$Id_Personne);
				
				$Ponderation=0;
				$Id_Contenu=estJour_Fixe_Id($DateJour,$Id_Personne);
				if($Id_Contenu<>""){
					$Id_Vacation=$Id_Contenu;
					$req="SELECT Ponderation FROM rh_typeabsence WHERE rh_typeabsence.Id=".$Id_Vacation;
					$resultJF=mysqli_query($bdd,$req);
					$nbJF=mysqli_num_rows($resultJF);
					
					if($nbJF>0){
						$Id_Vacation=0;
						
						$rowJF=mysqli_fetch_array($resultJF);
						$Ponderation=$rowJF['Ponderation'];
					}
				}
				
				//Vérifier si la personne n'a pas une vacation particulière ce jour là 
				$Id_Contenu=VacationPersonne($DateJour,$Id_Personne,$rowPresta['Id_Prestation'],$rowPresta['Id_Pole']);
				if($Id_Contenu>0){$Id_Vacation=$Id_Contenu;}
				//Vérifier si la personne n'est pas absente ou en congé ce jour là 
				$reqAbs="SELECT rh_absence.Id,rh_absence.NbHeureAbsJour,rh_absence.NbHeureAbsNuit,
							(SELECT Ponderation FROM rh_typeabsence WHERE rh_typeabsence.Id=(IF(rh_absence.Id_TypeAbsenceDefinitif>0,rh_absence.Id_TypeAbsenceDefinitif,rh_absence.Id_TypeAbsenceInitial))) AS Ponderation
							FROM rh_absence 
							LEFT JOIN rh_personne_demandeabsence 
							ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
							WHERE rh_personne_demandeabsence.Id_Personne=".$Id_Personne." 
							AND rh_absence.DateFin>='".$DateJour."' 
							AND rh_absence.DateDebut<='".$DateJour."' 
							AND rh_personne_demandeabsence.Suppr=0 
							AND rh_absence.Suppr=0  
							AND EtatN1<>-1
							AND EtatN2<>-1
							AND (EtatN2=1 OR DatePriseEnCompteRH>'0001-01-01') ";
				$resultAbs=mysqli_query($bdd,$reqAbs);
				$nbAbs=mysqli_num_rows($resultAbs);
				
				if($nbAbs>0){
					$Id_Vacation=0;
					
					$rowAbsence=mysqli_fetch_array($resultAbs);
					$Ponderation=$rowAbsence['Ponderation'];
				}
				
				if($Id_Vacation>0){
					//Calcul du nombre d'heure de la journée en fonction de sa vacation, de son contrat et de sa prestation 
					$tabContrat=PointagePersonneContrat($DateJour,$Id_Personne,$Id_Vacation,$jourSemaine);
					if(sizeof($tabContrat)>0){
						$NbHeures=$tabContrat[0]+$tabContrat[1]+$tabContrat[2];
						$Id_Contrat=IdContrat($Id_Personne,$DateJour);
						if($Id_Contrat>0){
							if(Id_TypeContrat($Id_Contrat)==10){
								$NbHeures=7;
							}
						}
					}
					else{
						$tab=PointagePrestationVacation($rowPresta['Id_Prestation'],$rowPresta['Id_Pole'],$Id_Vacation,$jourSemaine,$DateJour);
						if(sizeof($tab)>0){
							$NbHeures=$tab[0]+$tab[1]+$tab[2]+$tab[4];
						}
					}
					
				}
				else{
					$NbHeures=$Ponderation;
				}
				
				//Ajouter les Heures supplémentaires 
				$req="SELECT SUM(Nb_Heures_Jour) AS NbJour,SUM(Nb_Heures_Nuit) AS NbNuit
						FROM rh_personne_hs
						WHERE Suppr=0 
						AND Id_Personne=".$Id_Personne." 
						AND DateHS='".$DateJour."' 
						AND Etat4=1
						";
				$resultHS=mysqli_query($bdd,$req);
				$nbHS=mysqli_num_rows($resultHS);
				if($nbHS>0){
					$rowHS=mysqli_fetch_array($resultHS);
					$NbHeures=$NbHeures+$rowHS['NbJour']+$rowHS['NbNuit'];
				}
				
				//Ne pas compter les astreintes 
			}
		}
	}
	return $NbHeures;
}

function TauxPrestation($Id_Prestation){
	global $bdd;
	$Taux=0;
	$Id_Plateforme=0;
	
	//Récupérer les valeurs sur la prestaton
	$req="SELECT Id_Plateforme
			FROM new_competences_prestation
			WHERE Id=".$Id_Prestation."
			";
	$result=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($result);
	
	if($nbResulta>0){
		$rowPresta=mysqli_fetch_array($result);
		$Id_Plateforme=$rowPresta['Id_Plateforme'];
	}
	
	$req="SELECT Taux FROM rh_parametrage_cout 
		WHERE Suppr=0 
		AND Id_Prestation=".$Id_Prestation."
		AND Id_Plateforme=".$Id_Plateforme." 
		AND Id_TypeMetier=0
		";
	
	$result=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($result);
	
	if($nbResulta>0){
		$rowTaux=mysqli_fetch_array($result);
		$Taux=$rowTaux['Taux'];
	}
	else{
		$req="SELECT Taux FROM rh_parametrage_cout 
		WHERE Suppr=0 
		AND Id_Plateforme=".$Id_Plateforme." 
		AND Id_Prestation=0 
		AND Id_TypeMetier=0
		";
		$result=mysqli_query($bdd,$req);
		$nbResulta=mysqli_num_rows($result);

		if($nbResulta>0){
			$rowTaux=mysqli_fetch_array($result);
			$Taux=$rowTaux['Taux'];
		}
		else{
			$req="SELECT Taux FROM rh_parametrage_cout 
			WHERE Suppr=0 
			AND Id_Plateforme=0 AND Id_Prestation=0 AND Id_TypeMetier=0 ";
			$result=mysqli_query($bdd,$req);
			$nbResulta=mysqli_num_rows($result);
			
			if($nbResulta>0){
				$rowTaux=mysqli_fetch_array($result);
				$Taux=$rowTaux['Taux'];
			}
		}
	}
	return $Taux;
}


function creerMailDemandeRessource($Type,$Id_DemandeRessource)
{
	global $bdd;
	global $IdPosteResponsableRecrutement;
	global $IdPosteRecrutement;
	
	$Headers='From: "Extranet Daher industriel services DIS"<noreply.extranet@aaa-aero.com>'."\n";
	$Headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";
	
	
	$requete="SELECT recrut_demanderessource.Id,DateDemande,Id_Demandeur,Id_Prestation,Id_Metier,Nombre,Lieu,RaisonRefus,Suppr,
			DateBesoin,Horaire,Duree,MotifContrat,MotifContratSuite,MotifDemande,NbrTrouve,DescriptifPoste,Prerequis,EtatValidation,Langue,ExperienceDesPostes,Logiciel,
			(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
			(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) AS Id_Plateforme,
			(SELECT Libelle FROM new_competences_metier WHERE Id=Id_Metier) AS Metier,
			(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Demandeur) AS Demandeur,
			(SELECT EmailPro FROM new_rh_etatcivil WHERE Id=Id_Demandeur) AS EmailDemandeur,
			(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Validateur) AS Validateur,DateValidation,
			IF(
				recrut_demanderessource.EtatValidation='',
				0,
				IF(
					recrut_demanderessource.EtatValidation=1 AND recrut_demanderessource.EtatRecrutement='',
					1,
					IF(
						recrut_demanderessource.EtatValidation=1 AND recrut_demanderessource.EtatRecrutement=1,
						2,
						IF(
							recrut_demanderessource.EtatValidation=1 AND recrut_demanderessource.EtatRecrutement=2,
							3,
							IF(
								recrut_demanderessource.EtatValidation=-1,
								4,
								5
							)
						)
					)
				)
			)
			AS Etat
	FROM recrut_demanderessource
	WHERE recrut_demanderessource.Id=".$Id_DemandeRessource ;
	$result=mysqli_query($bdd,$requete);
	$row=mysqli_fetch_array($result);
	
	
	$req="SELECT DISTINCT EmailPro 
		FROM new_competences_personne_poste_plateforme 
		LEFT JOIN new_rh_etatcivil
		ON new_competences_personne_poste_plateforme.Id_Personne=new_rh_etatcivil.Id
		WHERE new_competences_personne_poste_plateforme.Id_Poste IN (".$IdPosteResponsableRecrutement.",".$IdPosteRecrutement.") 
		AND Id_Plateforme=".$row['Id_Plateforme']." ";
	$Result2=mysqli_query($bdd,$req);
	$Nb=mysqli_num_rows($Result2);
	$destinataire="";
	if($Nb>0)
	{
		while($Row2=mysqli_fetch_array($Result2))
		{
			if($destinataire<>""){$destinataire.=",";}
			if($Row2['EmailPro']<>""){$destinataire.=$Row2['EmailPro'];}
		}
	}
	
	$Objet="Demande de ressource n°".$Id_DemandeRessource." - ".$Type;
	$Message="	<html>
					<head><title>DEMANDE DE RESSOURCE</title></head>
					<body>
						<table >
							<tr>
								<td width='10%' style='background-color:#e9edf1'>N° demande :</td>
								<td width='15%' style='background-color:#e9edf1'>".$row['Id']."</td>
								<td width='10%' style='background-color:#e9edf1'>Etat :</td>
								<td width='15%' style='background-color:#e9edf1' colspan='4'>".$Type."</td>
							</tr>
							<tr>
								<td height='5'></td>
							</tr>
							<tr>
								<td width='10%'  style='background-color:#e9edf1'>Prestation :</td>
								<td width='20%' style='background-color:#e9edf1'>".$row['Prestation']."</td>
								<td  width='10%'  style='background-color:#e9edf1'>Lieu : </td>
								<td width='20%' style='background-color:#e9edf1'>".stripslashes($row['Lieu'])."</td>
								<td width='10%'  style='background-color:#e9edf1'>Métier :</td>
								<td width='20%' style='background-color:#e9edf1'>".stripslashes($row['Metier'])."</td>
							</tr>
							<tr><td height='4'></td></tr>
							<tr>
								<td width='10%'  style='background-color:#e9edf1'>Date démarrage souhaitée : </td>
								<td width='20%' style='background-color:#e9edf1'>".AfficheDateJJ_MM_AAAA($row['DateBesoin'])."</td>
								<td  width='10%'  style='background-color:#e9edf1'>Durée</td>
								<td width='20%' style='background-color:#e9edf1'>".stripslashes($row['Duree'])."</td>
								<td  width='10%'  style='background-color:#e9edf1'>Nombre : </td>
								<td width='20%' style='background-color:#e9edf1'>".stripslashes($row['Nombre'])."</td>
							</tr>
							<tr><td height='4'></td></tr>
							<tr>
								<td  width='10%'  style='background-color:#e9edf1'>Motif de la demande : </td>
								<td width='20%'style='background-color:#e9edf1'  colspan='3'>".stripslashes($row['MotifDemande'])."</td>
								<td  width='10%'  style='background-color:#e9edf1'>Horaire équipe : </td>
								<td width='20%' style='background-color:#e9edf1'>".stripslashes($row['Horaire'])."</td>
							</tr>
							<tr><td height='4'></td></tr>
							<tr>
								<td  width='10%'  style='background-color:#e9edf1'>Motif du contrat : </td>
								<td width='20%' style='background-color:#e9edf1' colspan='6'>".stripslashes($row['MotifContrat'])." ".stripslashes($row['MotifContratSuite'])."</td>
							</tr>
							<tr><td height='4'></td></tr>
							<tr>
								<td width='10%'  style='background-color:#e9edf1'>Descriptif du poste : </td>
								<td width='30%' style='background-color:#e9edf1' colspan='6'>".stripslashes($row['DescriptifPoste'])."</td>
							</tr>
							<tr><td height='4'></td></tr>
							<tr>
								<td width='10%'  style='background-color:#e9edf1'>Prérequis particulier :</td>
								<td width='30%' style='background-color:#e9edf1' colspan='6'>".stripslashes($row['Prerequis'])."</td>
							</tr>
							<tr><td height='4'></td></tr>
							<tr>
								<td width='10%' style='background-color:#e9edf1'>Logiciel / Informatique :</td>
								<td width='30%' style='background-color:#e9edf1' colspan='6'>".stripslashes($row['Logiciel'])."</td>
							</tr>
							<tr><td height='4'></td></tr>
							<tr>
								<td  width='10%'  style='background-color:#e9edf1'>Langues : </td>
								<td width='30%' style='background-color:#e9edf1' colspan='6'>".stripslashes($row['Langue'])."</td>
							</tr>
							<tr><td height='4'></td></tr>
							<tr>
								<td width='15%'  style='background-color:#e9edf1'>Expérience souhaitée <br>(si besoin de plusieurs postes définir la répartition) :</td>
								<td width='30%' style='background-color:#e9edf1' colspan='6'>".stripslashes($row['ExperienceDesPostes'])."</td>
							</tr> ";
		if($Type=="VALIDEE"){
			$Message.="<tr>
							<td width='10%'  style='background-color:#e9edf1'>Validation :</td>
							<td width='20%' style='background-color:#e9edf1' colspan='6'>Validée</td>
						</tr> ";
		}
		elseif($Type=="REFUSEE"){
			$Message.="<tr>
							<td width='10%'  style='background-color:#e9edf1'>Validation :</td>
							<td width='20%' style='background-color:#e9edf1' >Refusée</td>
							<td width='10%'  style='background-color:#e9edf1'>Raison du refus :</td>
							<td width='20%' style='background-color:#e9edf1' colspan='4'>".stripslashes($row['RaisonRefus'])."</td>
						</tr> ";
		}
		$Message.="
						</table>
					</body>
				</html>";
				
	$Email=$row['EmailDemandeur'];
	$Email="pfauge@aaa-aero.com";
	
	if($Email<>"")
	{
		if(mail($Email,$Objet,$Message,$Headers,'-f noreply.extranet@aaa-aero.com')){}
	}

}

function InfoCeJourSurCettePresta($Id_Personne,$DateJour,$Id_Prestation){
	global $bdd;
	
	$EnCPouRTT=0;
	$EnGardeEnfant=0;
	$EnMaladie=0;
	$EnMaladieInf3=0;
	$EnMaladieSup3=0;
	$EnAbs=0;
	$NbHeures=0;
	$tabDate = explode('-', $DateJour);
	$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
	$jourSemaine = date('w', $timestamp);
	$Id_Vacation=0;
	$EnEquipe=0;
	$Vacation="";
	$Montant=0;
	$nbHeureFormationVac=date('H:i',strtotime($DateJour.' 00:00:00'));
	$nbHeureFormation=date('H:i',strtotime($DateJour.' 00:00:00'));
	
	//Récupérer les valeurs sur la prestaton
	$req="SELECT Id_Prestation,Id_Pole 
			FROM rh_personne_mouvement
			LEFT JOIN new_competences_prestation
			ON rh_personne_mouvement.Id_Prestation=new_competences_prestation.Id
			WHERE rh_personne_mouvement.DateDebut<='".$DateJour."'
			AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$DateJour."')
			AND rh_personne_mouvement.EtatValidation=1 
			AND rh_personne_mouvement.Id_Prestation=".$Id_Prestation."
			AND rh_personne_mouvement.Suppr=0
			AND rh_personne_mouvement.Id_Personne=".$Id_Personne."
			";
	$result=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($result);
	
	if($nbResulta>0){
		$rowPresta=mysqli_fetch_array($result);
		
		//Récupérer l'Id_vacation de cette journée en fonction
		$Couleur=TravailCeJourDeSemaine($DateJour,$Id_Personne);
		if($Couleur<>""){
			$Id_Vacation=IdVacationCeJourDeSemaine($DateJour,$Id_Personne);
			$Id_Contenu=estJour_Fixe_Id($DateJour,$Id_Personne);
			if($Id_Contenu<>""){$Id_Vacation=0;}
			
			//Vérifier si la personne n'a pas une vacation particulière ce jour là 
			$Id_Contenu=VacationPersonne($DateJour,$Id_Personne,$rowPresta['Id_Prestation'],$rowPresta['Id_Pole']);
			if($Id_Contenu>0){$Id_Vacation=$Id_Contenu;}
			//Vérifier si la personne n'est pas absente ou en congé ce jour là 
			$reqAbs="SELECT rh_absence.Id,
						(SELECT Ponderation FROM rh_typeabsence WHERE rh_typeabsence.Id=(IF(rh_absence.Id_TypeAbsenceDefinitif>0,rh_absence.Id_TypeAbsenceDefinitif,rh_absence.Id_TypeAbsenceInitial))) AS Ponderation
						FROM rh_absence 
						LEFT JOIN rh_personne_demandeabsence 
						ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
						WHERE rh_personne_demandeabsence.Id_Personne=".$Id_Personne." 
						AND rh_absence.DateFin>='".$DateJour."' 
						AND rh_absence.DateDebut<='".$DateJour."' 
						AND rh_personne_demandeabsence.Suppr=0 
						AND rh_absence.Suppr=0  
						AND EtatN1<>-1
						AND EtatN2<>-1
						AND (EtatN2=1 OR DatePriseEnCompteRH>'0001-01-01') ";
			$resultAbs=mysqli_query($bdd,$reqAbs);
			$nbAbs=mysqli_num_rows($resultAbs);
			$Ponderation=0;
			if($nbAbs>0){
				$Id_Vacation=0;
				
				$rowAbsence=mysqli_fetch_array($resultAbs);
				$Ponderation=$rowAbsence['Ponderation'];
			}
			
			if($Id_Vacation>0){
				$req="SELECT Nom FROM rh_vacation WHERE Id=".$Id_Vacation;
				$resultVac=mysqli_query($bdd,$req);
				$nbVac=mysqli_num_rows($resultVac);
				if($nbVac>0){
					$rowVac=mysqli_fetch_array($resultVac);
					$Vacation=$rowVac['Nom'];
				}
				
				//Horaires de la personne
				$HeureDebutTravail="00:00:00";
				$HeureFinTravail="00:00:00";

				$tab=HorairesJournee($Id_Personne,$DateJour);
				if(sizeof($tab)>0){
					$HeureDebutTravail=$tab[0];
					$HeureFinTravail=$tab[1];
				}
			
				
				//Formation dans l'outil formation 
				$req="  SELECT
							form_session_date.DateSession,form_session_personne.Id_Personne,
							Heure_Debut,Heure_Fin,PauseRepas,HeureDebutPause,HeureFinPause
						FROM
							form_session_date 
							LEFT JOIN form_session ON form_session_date.Id_Session=form_session.Id
							LEFT JOIN form_session_personne ON form_session_personne.Id_Session=form_session.Id
						WHERE
							form_session_date.Suppr=0 
							AND form_session.Suppr=0
							AND form_session.Annule=0 
							AND form_session_date.DateSession='".$DateJour."'
							AND form_session_personne.Suppr=0
							AND form_session_personne.Id_Personne=".$Id_Personne." 
							AND form_session_personne.Validation_Inscription=1
							AND form_session_personne.Id_Session=form_session.Id
							AND Presence IN (0,1)
							 ";
						
				$resultSession=mysqli_query($bdd,$req);
				$nbSession=mysqli_num_rows($resultSession);
				
				$tab_Formation= array();
				if($nbSession>0){
					mysqli_data_seek($resultSession,0);
					while($rowForm=mysqli_fetch_array($resultSession)){
							$tab_Formation[] = array(
								'DateSession' => $rowForm['DateSession'], 
								'Id_Personne' => $rowForm['Id_Personne'], 
								'Heure_Debut' => $rowForm['Heure_Debut'], 
								'Heure_Fin' => $rowForm['Heure_Fin'], 
								'PauseRepas' => $rowForm['PauseRepas'], 
								'HeureDebutPause' => $rowForm['HeureDebutPause'],
								'HeureFinPause' => $rowForm['HeureFinPause']
							);
					}
				}
				
				if($HeureDebutTravail<>"00:00:00" && $HeureFinTravail<>"00:00:00"){
					//Formation 
					if(sizeof($tab_Formation)>0){
						$bTrouve=0;
						foreach($tab_Formation as $rowForm){
							
							if($rowForm['DateSession']==$DateJour){
								
								//Nombre total d'heure de formation
								$hF=strtotime($rowForm['Heure_Fin']);
								$hD=strtotime($rowForm['Heure_Debut']);
								$val=gmdate("H:i",$hF-$hD);
								$bTrouve=1;
								if($rowForm['PauseRepas']==1){
									$hFP=strtotime($rowForm['HeureFinPause']);
									$hDP=strtotime($rowForm['HeureDebutPause']);
									if($hDP<$hF && $hFP>$hD){
										if($hFP>$hF){$hFP=$hF;}
										if($hDP<$hD){$hDP=$hD;}
										$valPause=gmdate("H:i",$hFP-$hDP);
										$val=gmdate("H:i",strtotime($val)-strtotime($valPause));
									}
								}
								
								$nbHeureFormation=date('H:i',strtotime($nbHeureFormation." ".str_replace(":"," hour ",$val)." minute"));

								//Nombre d'heure pendant la vacation 
								if($HeureFinTravail<"03:00:00"){$HeureFinTravail="23:59:00";}
								$hFTravail=strtotime($HeureFinTravail);
								$hDTravail=strtotime($HeureDebutTravail);
								if($hDTravail>$hD || $hFTravail<$hF){
									if($hFTravail<$hF){$hF=$hFTravail;}
									if($hDTravail>$hD){$hD=$hDTravail;}
								}
								$val=gmdate("H:i",$hF-$hD);
								
								if($hDTravail>$hF || $hFTravail<$hD){
									$hF=0;
									$hD=0;
									$val=0;
								}
								
								if($hD<>0 && $hF<>0){
									if($rowForm['PauseRepas']==1){
										$hFP=strtotime($rowForm['HeureFinPause']);
										$hDP=strtotime($rowForm['HeureDebutPause']);
										if($hDP<$hF && $hFP>$hD){
											if($hFP>$hF){$hFP=$hF;}
											if($hDP<$hD){$hDP=$hD;}
											$valPause=gmdate("H:i",$hFP-$hDP);
											$val=gmdate("H:i",strtotime($val)-strtotime($valPause));
										}
									}
								}
								$nbHeureFormationVac=date('H:i',strtotime($nbHeureFormationVac." ".str_replace(":"," hour ",$val)." minute"));
							}
						}
					}
				}
				
			}
			else{
				$Vacation="";
			}
			
			if($Id_Vacation>0 && $Id_Vacation<>14){
				//Calcul du nombre d'heure de la journée en fonction de sa vacation, de son contrat et de sa prestation 
				$tabContrat=PointagePersonneContrat($DateJour,$Id_Personne,$Id_Vacation,$jourSemaine);
				if(sizeof($tabContrat)>0){
					$NbHeures=$tabContrat[0]+$tabContrat[1]+$tabContrat[2];
					$Id_Contrat=IdContrat($Id_Personne,$DateJour);
					if($Id_Contrat>0){
						if(Id_TypeContrat($Id_Contrat)==10){
							$NbHeures=7;
						}
					}
				}
				else{
					$tab=PointagePrestationVacation($rowPresta['Id_Prestation'],$rowPresta['Id_Pole'],$Id_Vacation,$jourSemaine,$DateJour);
					if(sizeof($tab)>0){
						$NbHeures=$tab[0]+$tab[1]+$tab[2]+$tab[4];
					}
				}
				
			}
			elseif($Id_Vacation==14){
				$NbHeures=7.4;
			}
			
			//Ajouter les Heures supplémentaires 
			$req="SELECT SUM(Nb_Heures_Jour) AS NbJour,SUM(Nb_Heures_Nuit) AS NbNuit
					FROM rh_personne_hs
					WHERE Suppr=0 
					AND Id_Personne=".$Id_Personne." 
					AND DateHS='".$DateJour."' 
					AND Etat4=1
					";
			$resultHS=mysqli_query($bdd,$req);
			$nbHS=mysqli_num_rows($resultHS);
			if($nbHS>0){
				$rowHS=mysqli_fetch_array($resultHS);
				$NbHeures=$NbHeures+$rowHS['NbJour']+$rowHS['NbNuit'];
			}
		}
		
		//Vérifier si la personne n'est pas absente ou en congé ce jour là 
		$reqAbs="SELECT rh_absence.Id
					FROM rh_absence 
					LEFT JOIN rh_personne_demandeabsence 
					ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
					WHERE rh_personne_demandeabsence.Id_Personne=".$Id_Personne." 
					AND rh_absence.DateFin>='".$DateJour."' 
					AND rh_absence.DateDebut<='".$DateJour."' 
					AND rh_personne_demandeabsence.Suppr=0 
					AND rh_absence.Suppr=0  
					AND IF(Id_TypeAbsenceDefinitif>0,Id_TypeAbsenceDefinitif,Id_TypeAbsenceInitial) IN (3,4,5,7,55)
					AND EtatN1<>-1
					AND EtatN2<>-1
					AND (EtatN2=1 OR DatePriseEnCompteRH>'0001-01-01') ";
		$resultAbs=mysqli_query($bdd,$reqAbs);
		$nbAbs=mysqli_num_rows($resultAbs);
		if($nbAbs>0){
			$EnCPouRTT=1;
		}
		
		$reqAbs="SELECT rh_absence.Id
					FROM rh_absence 
					LEFT JOIN rh_personne_demandeabsence 
					ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
					WHERE rh_personne_demandeabsence.Id_Personne=".$Id_Personne." 
					AND rh_absence.DateFin>='".$DateJour."' 
					AND rh_absence.DateDebut<='".$DateJour."' 
					AND rh_personne_demandeabsence.Suppr=0 
					AND rh_absence.Suppr=0  
					AND IF(Id_TypeAbsenceDefinitif>0,Id_TypeAbsenceDefinitif,Id_TypeAbsenceInitial) IN (26,30,60,61,65)
					AND EtatN1<>-1
					AND EtatN2<>-1
					AND (EtatN2=1 OR DatePriseEnCompteRH>'0001-01-01') ";
		$resultAbs=mysqli_query($bdd,$reqAbs);
		$nbAbs=mysqli_num_rows($resultAbs);
		if($nbAbs>0){
			$EnGardeEnfant=1;
		}
		
		$reqAbs="SELECT rh_absence.Id
					FROM rh_absence 
					LEFT JOIN rh_personne_demandeabsence 
					ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
					WHERE rh_personne_demandeabsence.Id_Personne=".$Id_Personne." 
					AND rh_absence.DateFin>='".$DateJour."' 
					AND rh_absence.DateDebut<='".$DateJour."' 
					AND rh_personne_demandeabsence.Suppr=0 
					AND rh_absence.Suppr=0  
					AND IF(Id_TypeAbsenceDefinitif>0,Id_TypeAbsenceDefinitif,Id_TypeAbsenceInitial) IN (1,77,23)
					AND EtatN1<>-1
					AND EtatN2<>-1
					AND (EtatN2=1 OR DatePriseEnCompteRH>'0001-01-01') ";
		$resultAbs=mysqli_query($bdd,$reqAbs);
		$nbAbs=mysqli_num_rows($resultAbs);
		if($nbAbs>0){
			//Vérifier si jour travaillé
			$JourTravail=TravailCeJourDeSemaine($DateJour,$Id_Personne);
			if($JourTravail<>""){
				$EnMaladie=1;
			}
		}
		
		$reqAbs="SELECT rh_absence.Id
					FROM rh_absence 
					LEFT JOIN rh_personne_demandeabsence 
					ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
					WHERE rh_personne_demandeabsence.Id_Personne=".$Id_Personne." 
					AND rh_absence.DateFin>='".$DateJour."' 
					AND rh_absence.DateDebut<='".$DateJour."' 
					AND rh_personne_demandeabsence.Suppr=0 
					AND rh_absence.Suppr=0  
					AND IF(Id_TypeAbsenceDefinitif>0,Id_TypeAbsenceDefinitif,Id_TypeAbsenceInitial) IN (0)
					AND EtatN1<>-1
					AND EtatN2<>-1
					AND (EtatN2=1 OR DatePriseEnCompteRH>'0001-01-01') ";
		$resultAbs=mysqli_query($bdd,$reqAbs);
		$nbAbs=mysqli_num_rows($resultAbs);
		if($nbAbs>0){
			$EnAbs=1;
		}
	}
	
	if($EnMaladie==1){
		$EnMaladieInf3=$EnMaladie;
		$EnMaladieSup3=$EnMaladie;
	
		$dateATester=$DateJour;
		$bValide=1;
		$nbJourAM=0;
		
		while($bValide==1){
			
			$req="SELECT rh_personne_demandeabsence.Id 
			FROM rh_personne_demandeabsence
			WHERE rh_personne_demandeabsence.Suppr=0 
			AND Id_Personne=".$Id_Personne."
			AND EtatN1<>-1
			AND EtatN2<>-1
			AND (EtatN2=1 OR DatePriseEnCompteRH>'0001-01-01')
			AND (SELECT COUNT(Id)
				FROM rh_absence 
				WHERE Suppr=0
				AND Id_Personne_DA=rh_personne_demandeabsence.Id
				AND IF(Id_TypeAbsenceDefinitif>0,Id_TypeAbsenceDefinitif,Id_TypeAbsenceInitial) IN (1,77,23)
				AND DateDebut<='".$dateATester."'
				AND DateFin>='".$dateATester."'
			)>0 ";
			$resultTest=mysqli_query($bdd,$req);
			$nbTest=mysqli_num_rows($resultTest);
			
			if($nbTest>0){
				$JourTravail=TravailCeJourDeSemaine($dateATester,$Id_Personne);
				if($JourTravail<>""){
					$nbJourAM++;
				}
				$dateATester=date('Y-m-d',strtotime($dateATester." - 1 day"));
			}
			else{
				$bValide=0;
			}
		}
		if($nbJourAM>3){$EnMaladieInf3=0;}
		if($nbJourAM<=3){$EnMaladieSup3=0;}
	}
	
	if(substr($Vacation,0,2)=="EJ" || substr($Vacation,0,2)=="ES" || substr($Vacation,0,2)=="EN"){$EnEquipe=1;}
	
	if($Id_Vacation>0){
		$Montant=6+MontantDeplacementCeJour($DateJour,$Id_Personne);
		$Id_Vacation=1;
	}
	$tab = array($EnCPouRTT,$EnGardeEnfant,$EnMaladieInf3,$EnMaladieSup3,$EnAbs,$Id_Vacation,$EnEquipe,$Vacation,$Montant,$NbHeures,$nbHeureFormationVac);
	return $tab;
}

function NombreHeuresTotalJourneeRepartitionV3($Id_Personne,$DateJour,$Id_Prestation){
	global $bdd;
	
	$NbHeures=0;
	$tabDate = explode('-', $DateJour);
	$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
	$jourSemaine = date('w', $timestamp);
	$Id_Vacation=0;
	$Vacation="";
	
	//Récupérer les valeurs sur la prestaton
	$req="SELECT Id_Prestation,Id_Pole 
			FROM rh_personne_mouvement
			LEFT JOIN new_competences_prestation
			ON rh_personne_mouvement.Id_Prestation=new_competences_prestation.Id
			WHERE rh_personne_mouvement.DateDebut<='".$DateJour."'
			AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$DateJour."')
			AND rh_personne_mouvement.EtatValidation=1 
			AND rh_personne_mouvement.Id_Prestation=".$Id_Prestation."
			AND rh_personne_mouvement.Suppr=0
			AND rh_personne_mouvement.Id_Personne=".$Id_Personne."
			";
	$result=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($result);
	
	if($nbResulta>0){
		$rowPresta=mysqli_fetch_array($result);
		
		//Récupérer l'Id_vacation de cette journée en fonction
		$Couleur=TravailCeJourDeSemaine($DateJour,$Id_Personne);
		if($Couleur<>""){
			$Id_Vacation=IdVacationCeJourDeSemaine($DateJour,$Id_Personne);
			$Id_Contenu=estJour_Fixe_Id($DateJour,$Id_Personne);
			if($Id_Contenu<>""){$Id_Vacation=0;}
			
			//Vérifier si la personne n'a pas une vacation particulière ce jour là 
			$Id_Contenu=VacationPersonne($DateJour,$Id_Personne,$rowPresta['Id_Prestation'],$rowPresta['Id_Pole']);
			if($Id_Contenu>0){$Id_Vacation=$Id_Contenu;}
			//Vérifier si la personne n'est pas absente ou en congé ce jour là 
			$reqAbs="SELECT rh_absence.Id,
						(SELECT Ponderation FROM rh_typeabsence WHERE rh_typeabsence.Id=(IF(rh_absence.Id_TypeAbsenceDefinitif>0,rh_absence.Id_TypeAbsenceDefinitif,rh_absence.Id_TypeAbsenceInitial))) AS Ponderation
						FROM rh_absence 
						LEFT JOIN rh_personne_demandeabsence 
						ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
						WHERE rh_personne_demandeabsence.Id_Personne=".$Id_Personne." 
						AND rh_absence.DateFin>='".$DateJour."' 
						AND rh_absence.DateDebut<='".$DateJour."' 
						AND rh_personne_demandeabsence.Suppr=0 
						AND rh_absence.Suppr=0  
						AND EtatN1<>-1
						AND EtatN2<>-1
						AND (EtatN2=1 OR DatePriseEnCompteRH>'0001-01-01') ";
			$resultAbs=mysqli_query($bdd,$reqAbs);
			$nbAbs=mysqli_num_rows($resultAbs);
			if($nbAbs>0){
				$Id_Vacation=0;
			}
			
			if($Id_Vacation>0 && $Id_Vacation<>14){
				//Calcul du nombre d'heure de la journée en fonction de sa vacation, de son contrat et de sa prestation 
				$tabContrat=PointagePersonneContrat($DateJour,$Id_Personne,$Id_Vacation,$jourSemaine);
				if(sizeof($tabContrat)>0){
					$NbHeures=$tabContrat[0]+$tabContrat[1]+$tabContrat[2];
					$Id_Contrat=IdContrat($Id_Personne,$DateJour);
					if($Id_Contrat>0){
						if(Id_TypeContrat($Id_Contrat)==10){
							$NbHeures=7;
						}
					}
				}
				else{
					$tab=PointagePrestationVacation($rowPresta['Id_Prestation'],$rowPresta['Id_Pole'],$Id_Vacation,$jourSemaine,$DateJour);
					if(sizeof($tab)>0){
						$NbHeures=$tab[0]+$tab[1]+$tab[2]+$tab[4];
					}
				}
				
			}
			elseif($Id_Vacation==14){
				$NbHeures=7.4;
			}
			
			//Ajouter les Heures supplémentaires 
			$req="SELECT SUM(Nb_Heures_Jour) AS NbJour,SUM(Nb_Heures_Nuit) AS NbNuit
					FROM rh_personne_hs
					WHERE Suppr=0 
					AND Id_Personne=".$Id_Personne." 
					AND DateHS='".$DateJour."' 
					AND Etat4=1
					";
			$resultHS=mysqli_query($bdd,$req);
			$nbHS=mysqli_num_rows($resultHS);
			if($nbHS>0){
				$rowHS=mysqli_fetch_array($resultHS);
				$NbHeures=$NbHeures+$rowHS['NbJour']+$rowHS['NbNuit'];
			}
		}
	}

	return $NbHeures;
}
?>
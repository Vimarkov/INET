<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");
require("Fonctions_Planning.php");

function chaine_aleatoire($nb_car, $chaine = 'azertyuiopqsdfghjklmwxcvbn123456789')
{
    $nb_lettres = strlen($chaine) - 1;
    $generation = '';
    for($i=0; $i < $nb_car; $i++)
    {
        $pos = mt_rand(0, $nb_lettres);
        $car = $chaine[$pos];
        $generation .= $car;
    }
    return $generation;
}

//Envoi au personnes de Bordeaux
$Requete="
SELECT DISTINCT Id_Personne,Nom,Prenom, Login, Motdepasse, IF(Email<>'',Email,EmailPro) AS Email,Date_Naissance
FROM rh_personne_mouvement
LEFT JOIN new_rh_etatcivil 
ON rh_personne_mouvement.Id_Personne=new_rh_etatcivil.Id
WHERE (new_rh_etatcivil.Id>0 
AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (9,10,13,17,19)
AND DateDebut<='".date('Y-m-d')."'
AND (DateFin >='".date('Y-m-d')."' OR DateFin<='0001-01-01'))
AND (SELECT COUNT(Id_Personne) 
	FROM new_competences_personne_plateforme 
	WHERE new_competences_personne_plateforme.Id_Personne=rh_personne_mouvement.Id_Personne
	AND new_competences_personne_plateforme.Id_Plateforme=14)=0
ORDER BY Nom, Prenom ";

$result2=mysqli_query($bdd,$Requete);
$nbResulta2=mysqli_num_rows($result2);
if($nbResulta2>0){
	$i=0;
	while($row=mysqli_fetch_array($result2)){
		if($row['Email']<>""){
			$i++;
			echo $row['Nom']." - ".$row['Prenom']." - ".$row['Email']."<br>";
			//GenererMailIdentifiantsExtranetRappel($row['Nom'],$row['Prenom'],$row['Login'],$row['Motdepasse'],$row['Date_Naissance'],$row['Email'],"FR");		
		}
	}
	echo "Compteur : ".$i;
}

?>
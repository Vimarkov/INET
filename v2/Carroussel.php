<div class="container" style="width:100%;">
  <div id="myCarousel" class="carousel slide" data-ride="carousel">
	<!-- Indicators -->
	<ol class="carousel-indicators">
		<li data-target="#myCarousel" data-slide-to="1" class="active"></li>
		<?php
		if(mysqli_num_rows($resAcc3=mysqli_query($bdd,"SELECT Id_Poste FROM new_competences_personne_poste_plateforme WHERE Id_Poste IN (".$IdPosteDirection.",".$IdPosteResponsablePlateforme.",".$IdPosteAssistantRH.",".$IdPosteResponsableRH.") AND Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29) AND Id_Personne =".$IdPersonneConnectee))>0
		|| mysqli_num_rows($resAcc3=mysqli_query($bdd,"SELECT Id_Poste FROM new_competences_personne_poste_prestation WHERE Id_Poste IN (".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteResponsableOperation.") AND Id_Prestation IN (SELECT Id FROM new_competences_prestation WHERE Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)) AND Id_Personne =".$IdPersonneConnectee))>0
		|| (mysqli_num_rows($resAcc3=mysqli_query($bdd,"SELECT Id_Plateforme FROM new_competences_personne_plateforme WHERE Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29) AND Id_Personne =".$IdPersonneConnectee))>0))
		{
		?>
			<li data-target="#myCarousel" data-slide-to="2"></li>
			<li data-target="#myCarousel" data-slide-to="3"></li>
		<?php 
		}
		?>
	</ol>

	<!-- Wrapper for slides -->
	<div class="carousel-inner" style="height:480px;">
		<div class="item active" style="margin-left:70px;padding:20px;">
			<img src="questions.png" alt="Questions" style="height:430px;">
			<div class="carousel-caption">
			  <p style="color:#ffffff;font-size:16px;"><a style="color:#ffffff;font-size:16px;" href="<?php echo $HTTPServeur;?>Outils/BoiteIdees.php">Envoyez-nous un message !</a></p>
			</div>
		</div>
		<?php
		if(mysqli_num_rows($resAcc3=mysqli_query($bdd,"SELECT Id_Poste FROM new_competences_personne_poste_plateforme WHERE Id_Poste IN (".$IdPosteDirection.",".$IdPosteResponsablePlateforme.",".$IdPosteAssistantRH.",".$IdPosteResponsableRH.") AND Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29) AND Id_Personne =".$IdPersonneConnectee))>0
		|| mysqli_num_rows($resAcc3=mysqli_query($bdd,"SELECT Id_Poste FROM new_competences_personne_poste_prestation WHERE Id_Poste IN (".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteResponsableOperation.") AND Id_Prestation IN (SELECT Id FROM new_competences_prestation WHERE Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)) AND Id_Personne =".$IdPersonneConnectee))>0
		|| (mysqli_num_rows($resAcc3=mysqli_query($bdd,"SELECT Id_Plateforme FROM new_competences_personne_plateforme WHERE Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29) AND Id_Personne =".$IdPersonneConnectee))>0))
		{
		?>
		<div class="item" style="margin-left:70px;padding:20px;">
			<img src="TalentBoost.png" alt="TalentBoost" style="height:430px;">
		</div>
		
		<div class="item" style="margin-left:70px;padding:20px;">
			<img src="EPE.png" alt="EPE" style="height:430px;">
			<div class="carousel-caption">
			   <p style="color:#ffffff;font-size:16px;">Préparez votre entretien via <a style="color:#ffffff;font-size:16px;" href="<?php echo $HTTPServeur;?>Outils/EPE/Tableau_De_Bord.php"><span style="text-decoration:underline;">EPE/EPP</span></a></p>
			</div>
		</div>
		<?php 
		}
		?>
	</div>

	<!-- Left and right controls -->
	<a class="left carousel-control" href="#myCarousel" data-slide="prev">
	  <span class="glyphicon glyphicon-chevron-left"></span>
	  <span class="sr-only">Previous</span>
	</a>
	<a class="right carousel-control" href="#myCarousel" data-slide="next">
	  <span class="glyphicon glyphicon-chevron-right"></span>
	  <span class="sr-only">Next</span>
	</a>
  </div>
</div>
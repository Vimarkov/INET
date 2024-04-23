<script type="text/javascript">
	function OuvreFenetreModif(Mode,Page,Dossier1,Dossier2,Id)
		{window.open("Outils/Modif.php?Mode="+Mode+"&Page="+Page+"&Dossier1="+Dossier1+"&Dossier2="+Dossier2+"&Id="+Id,"PageFichier","status=no,menubar=no,width=600,height=700");}
	function OuvrirImage(Page,Dossier1,Dossier2,Img)
		{window.open("Outils/OuvreImage.php?Page="+Page+"&Dossier1="+Dossier1+"&Dossier2="+Dossier2+"&Image="+Img,"PageImage","status=no,menubar=no,width=600,height=350,resizable=yes");}
	function OuvrirFichier(Page,Dossier1,Dossier2,Fic)
		{window.open("../Upload/Fichiers/"+Page+"/"+Dossier1+"/"+Dossier2+"/"+Fic,"PageFichier","status=no,menubar=no,width="+screen.width-10+",height="+screen.height-10+",resizable=yes");}
	function AfficheTelecharge(){window.status="Faites clic droit pour enregistrer le fichier";}
	function mettreEnVu(Id){
		$.ajax({
			url : 'Outils/Onboarding/Ajax_MettreEnVu.php',
			data : 'Id='+Id,
			dataType : "html",
			async : false,
			//affichage de l'erreur en cas de problème
			error:function(msg, string){
				},
			success:function(data){
					window.location.reload();
				}
		});
	}
	function OuvreFenetreAjoutFeedBack(){
		var w=window.open("Outils/Onboarding/Feedback.php?Mode=A&Id=0","Page","status=no,menubar=no,scrollbars=yes,width=1000,height=300");
		w.focus();
	}
		
      $( document ).ready( function() {

			$('body').flurry({height: 600,speed: 5000,small: 28,
		large: 68,});

			$('.toggle-snow').on('click', function(event) {

			  event.preventDefault();

			  try {
				$('body').flurry('destroy');
			  }
			  catch(err) {
				$('body').flurry();
			  }
			});
		  });
</script>
<style>
	html,body{
		background-color:#ffffff;
	}
</style>
<div class="conteneurTDB">
	<?php
		$DirFichier=$CheminOnBoarding;
		$reqPresta = "SELECT DISTINCT(new_competences_personne_prestation.Id_Prestation) 
			FROM new_competences_personne_prestation LEFT JOIN new_competences_prestation ON new_competences_personne_prestation.Id_Prestation = new_competences_prestation.Id 
			WHERE new_competences_personne_prestation.Id_Personne =".$IdPersonneConnectee." 
			AND new_competences_personne_prestation.Date_Debut <='".$dateDuJour ."' 
			AND new_competences_personne_prestation.Date_Fin >='".$dateDuJour ."'";
			
		if(!estInterimPourMenu(date('Y-m-d'),$_SESSION['Id_Personne']) && !estSousTraitantPourMenu($IdPersonneConnectee)
			&& mysqli_num_rows($resAcc=mysqli_query($bdd,$reqPresta))>0){
	?>
	<div class="BlocFeedback">
			<a style='text-decoration:none;cursor: pointer;' onclick="OuvreFenetreAjoutFeedBack()">
			<img width='100px' src='Images/DonnezAvis.PNG' border='0' />
			</a>
			<br>
			<a style='text-decoration:none;cursor: pointer;' onclick="OuvreFenetreAjoutFeedBack()">
				<?php if($_SESSION['Langue']=="FR"){echo "Donnez votre avis";}else{echo "Give your opinion";}?>
			</a>
	</div>
	<?php
		}
		$req="SELECT Id,Libelle,Document,TypeDocument,Id_Plateforme,DateCreation,Description,Image,Rubrique,
			(SELECT COUNT(Id) FROM onboarding_contenu_lu WHERE onboarding_contenu.Id=onboarding_contenu_lu.Id_Contenu AND onboarding_contenu_lu.Id_Personne=".$_SESSION['Id_Personne'].") AS Lu,
			(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS UER
			FROM onboarding_contenu
			WHERE Suppr=0
			AND Valide=1
			AND 
			(Id_Plateforme=0
			OR Id_Plateforme IN (SELECT Id_Plateforme FROM new_competences_personne_plateforme WHERE Id_Personne=".$_SESSION['Id_Personne'].")
			) ";
		if(estInterimPourMenu(date('Y-m-d'),$_SESSION['Id_Personne']) || estSousTraitantPourMenu($IdPersonneConnectee)
			|| mysqli_num_rows($resAcc=mysqli_query($bdd,$reqPresta))==0){
				$req.="AND VisibleUniquementSalarie=0 ";
			}
		$req.="ORDER BY DateCreation DESC";
		$result=mysqli_query($bdd,$req);
		$nbResulta=mysqli_num_rows($result);
		if ($nbResulta>0){
			while($row=mysqli_fetch_array($result)){
			?>
			<div class="BlocThemeTDB">
				<div class="ImageTDBActu">
					<?php 
						if($row['Image']<>""){
							if($row['Libelle']=="Enquête Formation Interne"){
								echo "<a class=\"Info\" href=\"https://docs.google.com/forms/d/e/1FAIpQLSfaa2_-f1hmn2MOdmmaxSQWx6HQGeIOFrin8BcgBI42_8hBVw/viewform\" target=\"_blank\">";
							}
							elseif($row['Libelle']=="HSE – Journée mondiale de la sécurité et de la santé au travail 2023"){
								echo "<a class=\"Info\" href=\"".$DirFichier.$row['Document']."\" onclick=\"window.open('".$DirFichier.$row['Document']."');mettreEnVu(".$row['Id'].")\" target=\"_blank\">";
							}
							elseif($row['Libelle']=="Conférence sur l'intelligence artificielle 15/06/2023"){
								echo "<a class=\"Info\" href=\"https://docs.google.com/forms/d/e/1FAIpQLSdmaWerCRGz6zqzexySduiDLYr-4QG8VRbZfhMNgYUUrVM8xA/viewform\" onclick=\"window.open('https://docs.google.com/forms/d/e/1FAIpQLSdmaWerCRGz6zqzexySduiDLYr-4QG8VRbZfhMNgYUUrVM8xA/viewform');mettreEnVu(".$row['Id'].")\" target=\"_blank\">";
							}
					?>
					<img class="imageAccueil" src="<?php echo $DirFichier.$row['Image'];?>" />
					<?php 
						if($row['Libelle']=="Enquête Formation Interne"
						|| $row['Libelle']=="HSE – Journée mondiale de la sécurité et de la santé au travail 2023"
						|| $row['Libelle']=="Conférence sur l'intelligence artificielle 15/06/2023"
						){
							echo "</a>";
						}
					}?>
					<div class="GrandTitreActu"><?php echo stripslashes($row['Rubrique']);?></div>
				</div>
				<div class="DateTDB">
					<?php
					echo AfficheDateJJ_MM_AAAA($row['DateCreation']);
					?>
				</div>
				<div class="blocUER">
					<?php
					if($row['Id_Plateforme']>0){
							echo "&nbsp;";
							echo "<span class='baliseUER'>";
							echo stripslashes($row['UER']);
							echo "</span>";
						}
					?>
				</div>
				<div class="TitreTDB"><?php echo stripslashes($row['Libelle']); ?></div>
				<div class="TexteTDB">
				<?php 
					echo nl2br(stripslashes($row['Description']));
				?>
				</div>
				<div class="ATelechargerTDB">
				<?php 
					if($row['Document']<>""){
						echo "<a class=\"Info\" href=\"".$DirFichier.$row['Document']."\" onclick=\"window.open('".$DirFichier.$row['Document']."');mettreEnVu(".$row['Id'].");\" target=\"_blank\">";
						if($row['TypeDocument']=="A télécharger"){
							if($_SESSION['Langue']=="FR"){echo "Lire la suite";}else{echo "Read more";}
						}
						else{
							if($_SESSION['Langue']=="FR"){echo "Lire la vidéo";}else{echo "Play the video";}
						}
						echo "</a>";
					}
				?>
				</div>
				<div class="blocVu">
				<?php
					/*if($row['Lu']>0){
						echo "&nbsp;&nbsp;";
						echo "<img width='15px' src='Images/Vu.png' border='0'>";
					}*/
				?>
				</div>
			</div>
			<?php } ?>
			<div class="blocTheme"></div>
		<?php
		}
	?>
	
	
</div>

<script>
  function GenererPlanning() {
	// Get the element.
	var element = document.getElementById('TDBDroite');

	// Generate the PDF.
	html2pdf().from(element).set({
	  margin: 1,
	  filename: 'test.pdf',
	  html2canvas: { scale: 2 },
	  jsPDF: {orientation: 'portrait', unit: 'in', format: 'letter', compressPDF: true}
	}).save();
  }
</script>
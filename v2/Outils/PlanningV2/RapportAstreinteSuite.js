function VerifChamps()
{
	if(document.getElementById('statut').value==0){
		if(document.getElementById('Langue').value=="FR"){
			if(document.getElementById('commentaire').value==""){alert("Veuillez ajouter un commentaire.");return false;}
		}
		else{
			if(document.getElementById('commentaire').value==""){alert("Please add a comment.");return false;}

		}
	}
	return true;
}
function AfficherRefus(){
	if(document.getElementById('statut').value==0){
		document.getElementById('trRaison').style.display="";
		document.getElementById('trCommentaire').style.display="";
	}
	else{
		document.getElementById('trRaison').style.display="none";
		document.getElementById('trCommentaire').style.display="none";
	}
}
function FermerEtRecharger(Menu,TDB,OngletTDB)
{
	window.opener.location="Liste_DemandeAstreinte.php?Menu="+Menu+"&TDB="+TDB+"&OngletTDB="+OngletTDB;
	window.close();
}
function EnregistrerRH2()
{
	document.getElementById('HorsContrat').innerHTML="";
	
	laDate=formulaire.dateDebut1.value;
	if(formulaire.datePriseEnCompte1.value!=""){
		laDate=formulaire.datePriseEnCompte1.value;
	}
	$.ajax({
		url : 'Ajax_HorsContratCeJour2.php',
		data : 'DateDebut1='+laDate+'&DateDebut2=&DateDebut3=&DateDebut4=&DateDebut5=&DateDebut6=&DateDebut7=&Id_Personnes='+formulaire.Id_Personne.value,
		dataType : "html",
		async : false,
		//affichage de l'erreur en cas de problème
		error:function(msg, string){
			
			},
		success:function(data){
			document.getElementById('HorsContrat').innerHTML=data;
			}
	});
	
	document.getElementById('HS').innerHTML="";
	document.getElementById('AS').innerHTML="";
	if(document.getElementById('HorsContrat').innerHTML.indexOf("attention.png")==-1){
		
		document.getElementById('HSJourNonT').innerHTML="";
		$.ajax({
			url : 'Ajax_HSHorsContratCeJour.php',
			data : 'DateDebut1='+laDate+'&DateDebut2=&DateDebut3=&DateDebut4=&DateDebut5=&DateDebut6=&DateDebut7=&Id_Personnes='+formulaire.Id_Personne.value,
			dataType : "html",
			async : false,
			//affichage de l'erreur en cas de problème
			error:function(msg, string){
				
				},
			success:function(data){
				document.getElementById('HSJourNonT').innerHTML=data;
				}
		});
		if(document.getElementById('HSJourNonT').innerHTML.indexOf("attention.png")!=-1){return false;}	
		
		$.ajax({
			url : 'Ajax_AstreinteCeJour4.php',
			data : 'DateDebut1='+laDate+'&DateDebut2=&DateDebut3=&DateDebut4=&DateDebut5=&DateDebut6=&DateDebut7=&Id_Personnes='+formulaire.Id_Personne.value+'&Id_Astreinte='+formulaire.Id.value,
			dataType : "html",
			async : false,
			//affichage de l'erreur en cas de problème
			error:function(msg, string){
				
				},
			success:function(data){
				document.getElementById('AS').innerHTML=data;
				}
		});
		
		document.getElementById('HS').innerHTML="";
		if(document.getElementById('AS').innerHTML.indexOf("attention.png")==-1){
			$.ajax({
				url : 'Ajax_HSCeJour5.php',
				data : 'DateDebut1='+laDate+'&DateDebut2=&DateDebut3=&DateDebut4=&DateDebut5=&DateDebut6=&DateDebut7=&Id_Personnes='+formulaire.Id_Personne.value,
				dataType : "html",
				async : false,
				//affichage de l'erreur en cas de problème
				error:function(msg, string){
					
					},
				success:function(data){
					document.getElementById('HS').innerHTML=data;
					}
			});
			//Récupérer la partie entre les balises <lesHS></lesHS>
			HS=document.getElementById('HS').innerHTML.substring(document.getElementById('HS').innerHTML.indexOf("lesHSDEBUT")+10,document.getElementById('HS').innerHTML.indexOf("lesHSFIN"));
			continu=1;
			if(HS!=""){
			if(document.getElementById('Langue').value=="FR"){
					question="Les personnes suivantes ont des heures supplémentaires déclarées ces jours là : \n"+HS+" \nVoulez vous continuez ?";
				}
				else{
					question="The following people have overtime declared on these days : \n"+HS+" \nDo you want to continue?";
				}
				if(window.confirm(question)){
					continu=1;
				}
				else{
					continu=0;
				}
			}
			if(continu==1){
				//Verifier si la personne a des absences injustifiées ce jour là
				document.getElementById('ABS_INJ').value="";
				$.ajax({
					url : 'Ajax_AbsenceCeJour3.php',
					data : 'DateDebut1='+laDate+'&DateDebut2=&DateDebut3=&DateDebut4=&DateDebut5=&DateDebut6=&DateDebut7=&Id_Personnes='+formulaire.Id_Personne.value,
					dataType : "html",
					async : false,
					//affichage de l'erreur en cas de problème
					error:function(msg, string){
						
						},
					success:function(data){
						document.getElementById('ABS_INJ').innerHTML=data;
						}
				});
				if(document.getElementById('ABS_INJ').innerHTML.indexOf("attention")==-1){
					document.getElementById('ABS').innerHTML="";
					$.ajax({
						url : 'Ajax_AbsenceCeJour2.php',
						data : 'DateDebut1='+laDate+'&DateDebut2=&DateDebut3=&DateDebut4=&DateDebut5=&DateDebut6=&DateDebut7=&Id_Personnes='+formulaire.Id_Personne.value,
						dataType : "html",
						async : false,
						//affichage de l'erreur en cas de problème
						error:function(msg, string){
							
							},
						success:function(data){
							document.getElementById('ABS').innerHTML=data;
							}
					});
					//Récupérer la partie entre les balises <lesHS></lesHS>
					ABS=document.getElementById('ABS').innerHTML.substring(document.getElementById('ABS').innerHTML.indexOf("lesABSDEBUT")+11,document.getElementById('ABS').innerHTML.indexOf("lesABSFIN"));
					if(ABS!=""){
						if(document.getElementById('Langue').value=="FR"){
							question="Les personnes suivantes ont des congés déclarés ces jours là : \n"+ABS+" \nVoulez vous continuez ?";
						}
						else{
							question="The following people have holidays declared on these days : \n"+ABS+" \nDo you want to continue?";
						}
						if(window.confirm(question)){
							var bouton = "<input style='display:none;' class='Bouton' type='submit' id='EnregistrerRH' name='EnregistrerRH' value='Enregistrer'>";
							document.getElementById('Ajouter').innerHTML=bouton;
							var evt = document.createEvent("MouseEvents");
							evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
							document.getElementById("EnregistrerRH").dispatchEvent(evt);
							document.getElementById('Ajouter').innerHTML="";
						}
					}
					else{
						var bouton = "<input style='display:none;' class='Bouton' type='submit' id='EnregistrerRH' name='EnregistrerRH' value='Enregistrer'>";
						document.getElementById('Ajouter').innerHTML=bouton;
						var evt = document.createEvent("MouseEvents");
						evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
						document.getElementById("EnregistrerRH").dispatchEvent(evt);
						document.getElementById('Ajouter').innerHTML="";
					}
				}
			}
		}
	}
}
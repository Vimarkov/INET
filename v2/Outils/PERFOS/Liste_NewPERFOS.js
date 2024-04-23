		function RecapitulatifExcel() {
			if (document.getElementById('dateDebut').value == "") {
				window.alert("La date de début est vide !");
				exit(0);
			}
			if (document.getElementById('dateFin').value == "") {
				window.alert("La date de fin est vide !");
				exit(0);
			}
			var debut = document.getElementById('dateDebut').value.substring(6,10)+"-"+document.getElementById('dateDebut').value.substring(3,5)+"-"+document.getElementById('dateDebut').value.substring(0,2);
			var fin =	document.getElementById('dateFin').value.substring(6,10)+"-"+document.getElementById('dateFin').value.substring(3,5)+"-"+document.getElementById('dateFin').value.substring(0,2);
			var w=window.open("Recapitulatif.php?debut="+debut+"&fin="+fin+"");
			w.focus();
			}		
		function OuvreFenetreAjoutperfos(Id_Personne,Id_Prestation,Id_Pole,dateEnvoi)
			{var w=window.open("ModifNewPERFOS.php?Mode=A&Id_Personne="+Id_Personne+"&Id_Prestation="+Id_Prestation+"&Id_Pole="+Id_Pole+"&dateEnvoi="+dateEnvoi,"Pagenew_perfos","status=no,menubar=no,width=1100,height=650,scrollbars=1");
			w.focus();
			}
		function OuvreFenetreSupprperfos(Id_perfos,Id_Personne,Id_Prestation,Id_Pole,dateEnvoi){
			question="Êtes-vous sûre de vouloir supprimer le SQCDPF ?";
			if(window.confirm(question)){
				var w=window.open("ModifNewPERFOS.php?Mode=S&Id_perfos="+Id_perfos+"&Id_Personne="+Id_Personne+"&Id_Prestation="+Id_Prestation+"&Id_Pole="+Id_Pole+"&dateEnvoi="+dateEnvoi,"Pagenew_perfos","status=no,menubar=no,width=1100,height=650,scrollbars=1");
				w.focus();
			}
		}
		function OuvreFenetreConsultperfos(Id_perfos)
			{var w=window.open("ConsultNewPERFOS.php?Id_perfos="+Id_perfos,"PageConsultperfos","status=no,menubar=no,width=1000,height=550");
			w.focus();
			}
		function OuvreFenetreModifperfos(Id_perfos, Id_Personne,Id_Prestation,Id_Pole,dateEnvoi)
			{var w=window.open("ModifNewPERFOS.php?Mode=M&Id_perfos="+Id_perfos+"&Id_Personne="+Id_Personne+"&Id_Prestation="+Id_Prestation+"&Id_Pole="+Id_Pole+"&dateEnvoi="+dateEnvoi,"Pagenew_perfos","status=no,menubar=no,width=1100,height=650,scrollbars=1");
			w.focus();
			}
		function OuvreFenetreDestinataireperfos(Id_Prestation,Id_Pole,Id_Personne)
			{var w=window.open("DestinatairePERFOS.php?Id_Prestation="+Id_Prestation+"&Id_Pole="+Id_Pole+"&Id_Personne="+Id_Personne,"PageMailnew_perfos","status=no,menubar=no,width=800,height=400");
			w.focus();
			}
		function EnvoyerMailperfos(Id_perfos,Id_Personne){
			question="Êtes-vous sûre de vouloir envoyer le  SQCDPF par Email ?";
			if(window.confirm(question)){
			window.open("EnvoyerMailNewPERFOS.php?Id_perfos="+Id_perfos+"&Id_Personne="+Id_Personne,"PageEnvoyerMailperfos","status=no,menubar=no,width=300,height=200");}
		}
		function OuvreFenetreFrequence(Id_Personne)
			{window.open("FrequencePERFOS.php?Id_Personne="+Id_Personne,"PageFrequence","status=no,menubar=no,scrollbars=1,width=920,height=370");}
		function Excel_BL(){
			var listeWPTacheNonCoche = "";
			document.getElementById('listeWP').value="";
			var inputs = document.getElementsByTagName('INPUT');
			for(l=0;l<inputs.length;l++){
				if(inputs[l].type == "checkbox") {
					if(inputs[l].id.substr(0,3)=="WP_"){
						if(inputs[l].checked == true){
							document.getElementById('listeWP').value+=inputs[l].id.substr(3)+";";
						}
					}
					else if(inputs[l].id.substr(0,3)=="_WP"){
						if(document.getElementById("WP_"+inputs[l].id.substr(4,inputs[l].id.indexOf("Tache_")-4)).checked == true){
							if(inputs[l].checked == false){
								listeWPTacheNonCoche+=inputs[l].id.substr(4,inputs[l].id.indexOf("Tache_")-4)+"_"+inputs[l].id.substr(inputs[l].id.indexOf("Tache_")+6)+";";
							}
						}
					}
				}
			}
			if(document.getElementById('dateDebut').value!="" && document.getElementById('dateFin').value!=""){
				if(document.getElementById('wpSeparare').checked==false){
					var w=window.open("Extract_BL.php?WP="+document.getElementById('listeWP').value+"&DateDebut="+document.getElementById('dateDebut').value+"&DateFin="+document.getElementById('dateFin').value+"&Tache="+listeWPTacheNonCoche,"PageExtract","status=no,menubar=no,scrollbars=yes,width=350,height=50");
					w.focus();
				}
				else{
					var w=window.open("Extract_BLSepare.php?WP="+document.getElementById('listeWP').value+"&DateDebut="+document.getElementById('dateDebut').value+"&DateFin="+document.getElementById('dateFin').value+"&Tache="+listeWPTacheNonCoche,"PageExtract","status=no,menubar=no,scrollbars=yes,width=350,height=50");
					w.focus();
				
				}
			}
		}
		function Affiche_Masque(WP){
			var SourceImage = document.getElementById('PlusMoins_'+WP).src;
			var result = SourceImage.substring(SourceImage.length -6, SourceImage.length);
			var Table_TR = document.getElementsByClassName('WP_'+WP);
			if(result == "us.gif"){
				document.getElementById('PlusMoins_'+WP).src="../../Images/Moins.gif";
				for(l=0;l<Table_TR.length+1;l++){Table_TR[l].style.display = 'table-row';}
			}
			else{
				document.getElementById('PlusMoins_'+WP).src="../../Images/Plus.gif";
				for(l=0;l<Table_TR.length+1;l++){Table_TR[l].style.display = 'none';}
			}
		}
		function cocherTache(WP){
			var inputs = document.getElementsByTagName('INPUT');
			for(i=0;i<inputs.length;i++){
				if(inputs[i].id.indexOf('WP_'+WP+'Tache_')>0){
					inputs[i].checked = document.getElementById('WP_'+WP).checked;
				}
			}
		}
		function ModifierDateFactu(langue){
			$.ajax({
				url : 'ModifierDateFactu.php',
				data : 'Date='+document.getElementById('dateFacturation').value,
			});
			if(langue=="EN"){
				alert('Billing date changed');
			}
			else{
				alert('La date de facturation a été modifiée');
			}
		}
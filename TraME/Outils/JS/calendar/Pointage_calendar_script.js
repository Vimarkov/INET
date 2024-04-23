var leId=0;
Liste_Personne = new Array();
function nombre(champ){
	var chiffres = new RegExp("[0-9\.]"); /* Modifier pour : var chiffres = new RegExp("[0-9]"); */
	var verif;
	var points = 0; /* Supprimer cette ligne */

	for(x = 0; x < champ.value.length; x++)
	{
	verif = chiffres.test(champ.value.charAt(x));
	if(champ.value.charAt(x) == "."){points++;} /* Supprimer cette ligne */
	if(points > 1){verif = false; points = 1;} /* Supprimer cette ligne */
	if(verif == false){champ.value = champ.value.substr(0,x) + champ.value.substr(x+1,champ.value.length-x+1); x--;}
	}
}
$(function(){
    /*########################################################################################*/
    /*###########################              MODULE AGENDA                     #############*/
    /*########################################################################################*/


    /* Taille des events  PFE OK */
    var td_width=$(".calendar_td").width();
    $(".calendar_event").css({
        "width" : td_width*0.98,
        "margin-left" : (td_width-(td_width*0.98))/2
    });

    /*nouvel event planning*/
    $(".calendar_tdG").dblclick(function(e){
        var agenda_first_id=0;
        var position_choisie=e.pageY-$(this).position().top;
        var Id_Select=($(this).attr("id"));
		var Semaine = Id_Select.substr(Id_Select.indexOf("_")+1,2);
		var Annee = Id_Select.substr(Id_Select.indexOf("/")+1);
		var Id_Preparateur = Id_Select.substr(0,Id_Select.indexOf("Hpl_"));
		var valeurHeure = document.getElementById(Id_Select).innerHTML;
		var Id_Select2=Id_Preparateur+"Hpo_"+Semaine+"/"+Annee;
		var semaineAnnee = Annee + Semaine;
		if(valeurHeure!=""){valeurHeure=valeurHeure.substr(0,valeurHeure.indexOf("h"));}
		document.getElementById("new_event_nbH").value=valeurHeure;
        /*dialog de remplissage*/
        $("#gen_new_content").dialog({
            bgiframe: true,
            resizable: true,
            height:150,
            width:300,
            modal: true,
            beforeclose: function(event, ui) {
                $(this).dialog('destroy');
                $("#new_event_nbH").val("");
            },
			buttons: {
				'Enregistrer': function() {
					$(this).dialog('destroy');
					var new_nbH=$("#new_event_nbH").val();

					/*creation de l'event dans la bdd*/
					var url_create="EventPlanning.php";
					$.ajax({
						url : url_create,
						data : 'NbH='+new_nbH+'&Semaine='+Semaine+'&Annee='+Annee+'&Id_Preparateur='+Id_Preparateur,
						async:false,
					});
					if(new_nbH==""){
						document.getElementById(Id_Select).innerHTML="";
					}
					else{
						document.getElementById(Id_Select).innerHTML=new_nbH+"h";
					}
					if(document.getElementById(Id_Select).style.backgroundColor!=document.getElementById("vert").style.backgroundColor){
						if(new_nbH!="" && new_nbH!=0){
							var heurePoint = 0;
							if(document.getElementById(Id_Select2).innerHTML!=""){
								heurePoint=document.getElementById(Id_Select2).innerHTML.substr(0,document.getElementById(Id_Select2).innerHTML.indexOf("h"));
							}
							if(document.getElementById('semaineAnnee').value > semaineAnnee){
								document.getElementById(Id_Select).style.backgroundColor="#ff0000";
								document.getElementById(Id_Select2).style.backgroundColor="#ff0000";
							}
							else if(document.getElementById('semaineAnnee').value == semaineAnnee){
								if(heurePoint==0){
									document.getElementById(Id_Select).style.backgroundColor="#ff0000";
									document.getElementById(Id_Select2).style.backgroundColor="#ff0000";
								}
								else if(parseFloat(new_nbH)<=parseFloat(heurePoint)){
									document.getElementById(Id_Select).style.backgroundColor="#ffc000";
									document.getElementById(Id_Select2).style.backgroundColor="#ffc000";
								}
								else{
									document.getElementById(Id_Select).style.backgroundColor="#ffffff";
									document.getElementById(Id_Select2).style.backgroundColor="#ffffff";
								}
							}
						}
						else{
							document.getElementById(Id_Select).style.backgroundColor="#ffffff";
							document.getElementById(Id_Select2).style.backgroundColor="#ffffff";
						}
					}
					$("#new_event_nbH").val("");
				},
				'Annuler': function() {
					$(this).dialog('destroy');
					$("#new_event_nbH").val("");
				},
			},
        });
		
		if(document.getElementById('langue').value=="EN"){
			$(":button:contains('Annuler')").html("Cancel") ;
			$(":button:contains('Enregistrer')").html("Save") ;
		}
    });
	
	/*nouvel event pointage*/
    $(".calendar_tdD").dblclick(function(e){
        var agenda_first_id=0;
        var position_choisie=e.pageY-$(this).position().top;
        var Id_Select=($(this).attr("id"));
		var Semaine = Id_Select.substr(Id_Select.indexOf("_")+1,2);
		var Annee = Id_Select.substr(Id_Select.indexOf("/")+1);
		var Id_Preparateur = Id_Select.substr(0,Id_Select.indexOf("Hpo_"));
		var valeurHeure = document.getElementById(Id_Select).innerHTML;
		var Id_Select2=Id_Preparateur+"Hpl_"+Semaine+"/"+Annee;
		var valeurHeure2 = document.getElementById(Id_Select2).innerHTML;
		var semaineAnnee = Annee + Semaine;
		
		for(i=0;i<Liste_Personne.length;i++){
			if (Liste_Personne[i][0]==Id_Preparateur){
				document.getElementById("collab").innerHTML=Liste_Personne[i][1];
			}
		}
		document.getElementById("HPlanning").innerHTML=valeurHeure2;
		document.getElementById("HPointage").innerHTML=valeurHeure;
		if(valeurHeure!=""){valeurHeure=valeurHeure.substr(0,valeurHeure.indexOf("h"));}
		if(valeurHeure2!=""){valeurHeure2=valeurHeure2.substr(0,valeurHeure2.indexOf("h"));}
		
        /*dialog de remplissage*/
        $("#gen_valid_content").dialog({
            bgiframe: true,
            resizable: true,
            height:170,
            width:350,
            modal: true,
            beforeclose: function(event, ui) {
                $(this).dialog('destroy');
                $("#new_event_nbH").val("");
            },
			buttons: {
				'Valider': function() {
					$(this).dialog('destroy');

					/*creation de l'event dans la bdd*/
					valide=0;
					if(document.getElementById(Id_Select).style.backgroundColor!=document.getElementById("vert").style.backgroundColor){
						valide=1;
					}
					var url_create="ValiderPointage.php";
					$.ajax({
						url : url_create,
						data : 'Semaine='+Semaine+'&Annee='+Annee+'&Id_Preparateur='+Id_Preparateur+'&Valide='+valide,
						async:false,
					});
					if(document.getElementById(Id_Select).style.backgroundColor==document.getElementById("vert").style.backgroundColor){
						if(valeurHeure2!="" && valeurHeure2!=0){
							if(document.getElementById('semaineAnnee').value > semaineAnnee){
								document.getElementById(Id_Select).style.backgroundColor="#ff0000";
								document.getElementById(Id_Select2).style.backgroundColor="#ff0000";
							}
							else if(document.getElementById('semaineAnnee').value == semaineAnnee){
								if(valeurHeure==0){
									document.getElementById(Id_Select).style.backgroundColor="#ff0000";
									document.getElementById(Id_Select2).style.backgroundColor="#ff0000";
								}
								else if(parseFloat(valeurHeure2)<=parseFloat(valeurHeure)){
									document.getElementById(Id_Select).style.backgroundColor="#ffc000";
									document.getElementById(Id_Select2).style.backgroundColor="#ffc000";
								}
								else{
									document.getElementById(Id_Select).style.backgroundColor="#ffffff";
									document.getElementById(Id_Select2).style.backgroundColor="#ffffff";
								}
							}
							else{
								document.getElementById(Id_Select).style.backgroundColor="#ffffff";
								document.getElementById(Id_Select2).style.backgroundColor="#ffffff";
							}
						}
						else{
							document.getElementById(Id_Select).style.backgroundColor="#ffffff";
							document.getElementById(Id_Select2).style.backgroundColor="#ffffff";
						}
					}
					else{
						document.getElementById(Id_Select).style.backgroundColor="#92d050";
						document.getElementById(Id_Select2).style.backgroundColor="#92d050";
					}
				},
				'D\351tails/Modifier': function() {
					$(this).dialog('destroy');
					var w=window.open("PlanningResp.php?Id="+Id_Preparateur+"&Semaine="+Semaine+"&Annee="+Annee+"&DateEC="+document.getElementById('laDateEC').value+"","Pointage","status=no,menubar=no,scrollbars=yes,width=1000,height=850");
					w.focus();
				},
				'Annuler': function() {
					$(this).dialog('destroy');
				},
			},
        });
		if(document.getElementById(Id_Select).style.backgroundColor!=document.getElementById("vert").style.backgroundColor){
			if(document.getElementById('langue').value=="EN"){
				$(":button:contains('Valider')").html("Validate") ;
				$(":button:contains('D\351tails/Modifier')").html("Details/Edit") ;
			}
		}
		else{
			if(document.getElementById('langue').value=="EN"){
				$(":button:contains('Valider')").html("Do not validate") ;
				$(":button:contains('D\351tails/Modifier')").html("Details") ;
			}
			else{
				$(":button:contains('Valider')").html("D\351valider") ;
				$(":button:contains('D\351tails/Modifier')").html("D\351tails") ;
			}
		}
		if(document.getElementById('langue').value=="EN"){
			$(":button:contains('Annuler')").html("Cancel") ;
		}
    });
	
    /*------------------   ARRONDIS PFE OK  --------------------*/
    $('div#container_top').corner("top cc:#4E6257");
    $('div#header_content').corner("bottom cc:#4E6257");
    $('div#visu_page_perso').corner();
    $('div#footer').corner("bottom")
    $('.lab_form').corner();
    $('.identifiant').corner("round 9px");
    $('.password').corner("round 9px");
    $('#visu_mail').corner("round 9px cc:#CCD9C8");
    $('.infosMail').corner("round 9px cc:#CCD9C8");
    $('.contenuMail').corner();
    $('.form_contenu').corner("round 9px");
    $('.form_titre').corner("round 9px");
    $('.form_cible').corner("round 9px");
    $('#ajout_galerie').corner("round 5px");
    $('#info_activation_module').corner();
    $('.input_form').corner();
    $('.form_list').corner();
    $('.evenement_list_depart').corner();
    $('.evenement_list_fin').corner();
    $('.form_list_visible').corner();
    $('.switcher_content_content').corner("left");
    $('.calendar_event').corner("cc:#fff");
    $('.calendar_event_date').corner("top cc:#fff");
    $(".switcher_agenda_inside").corner("top");
    $("div#select_agenda").corner("top");
    $(".select_agenda_selector").corner("round 4px");
});





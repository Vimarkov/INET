Liste_Presta = new Array();
Liste_WP = new Array();
var leId=0;
ListePlanning= new Array();
function RechargerTache2(Langue){
	var i;
	var sel="";
	var isElement = false;
	var bValide = true;
	sel ="<select class='lab' name='new_event_tache2' id='new_event_tache2'>";
	for(i=0;i<Liste_Tache_WP.length;i++){
		if (Liste_Tache_WP[i][1]==document.getElementById('new_event_wp2').value && Liste_Tache_WP[i][2]=="0"){
			sel= sel + "<option value='"+Liste_Tache_WP[i][0];
			sel= sel + "'>"+Liste_Tache_WP[i][3]+"</option>";
			isElement = true;
		}
	}
	if(isElement == false){sel= sel + "<option value='0' selected></option>";}
	sel =sel + "</select>";
	document.getElementById('divTache2').innerHTML=sel;
}
function RechargerWP2(Langue){
	var i;
	var sel="";
	var isElement = false;
	var bValide = true;
	sel ="<select class='lab' name='new_event_wp2' id='new_event_wp2' onchange='RechargerTache2()'>";
	for(i=0;i<Liste_WP.length;i++){
		if (Liste_WP[i][1]==document.getElementById('new_event_presta2').value){
			sel= sel + "<option value='"+Liste_WP[i][0];
			sel= sel + "'>"+Liste_WP[i][2]+"</option>";
			isElement = true;
		}
	}
	if(isElement == false){sel= sel + "<option value='0' selected></option>";}
	sel =sel + "</select>";
	document.getElementById('divWP2').innerHTML=sel;
	RechargerTache2();
}
$(function(){
    /*########################################################################################*/
    /*###########################              MODULE AGENDA                     #############*/
    /*########################################################################################*/

	//0.98
    /* Taille des events  PFE OK */
	if(document.getElementById('leCalendarTD').value=="calendar_td"){
		var td_width=$(".calendar_td").width();
		var td_width2=$(".calendar_td").width();
	}
	else{
		var td_width=$(".calendar2_td").width();
		var td_width2=$(".calendar2_td").width();
	}
    $(".calendar_event").css({
        "width" : td_width*1,
        "margin-left" : (td_width-(td_width*1))/2
    });
	
	/* Taille des events 2  PFE OK */
    $(".calendar2_event").css({
        "width" : td_width2*1,
        "margin-left" : (td_width2-(td_width2*1))/2
    });
	
	/* Taille des events Autre  PFE OK */
	if(document.getElementById('leCalendarTD').value=="calendar_td"){
		var td_widthAutre=$(".calendar_td").width();
	}
	else{
		var td_widthAutre=$(".calendar2_td").width();
	}
    $(".calendarAutre_event").css({
        "width" : td_widthAutre*1,
        "margin-left" : (td_widthAutre-(td_widthAutre*1))/2
    });

	/*  Déplacement event */
    $(".calendar_event").draggable({
        containment: "parent",
        grid: [1, 5],
        delay: 100,
        drag: function(event, ui) {
            var object_drop = $(this);
            var object_position=object_drop.position();
            var this_position=$(this).parent().position();
            current_position=object_position.top - this_position.top - 1;
            //placement de l'evenement
            var marg_css=object_drop.css("margin-top");
            var marg_css_value=parseInt(marg_css.replace(".px",""));
            var margin_top=marg_css_value+current_position;
            if(margin_top<0)margin_top=0;
            margin_top=parseInt(margin_top);
            //changement affichage horaire
            var id_event=object_drop.attr("id");

            var depart_en_sec=(((margin_top*2/30)/4-1)*60)*60;
            var depart_en_millisec=Math.round(depart_en_sec*1000);
            var height_css=object_drop.css("height");
            var height_css_value=parseInt(height_css.replace(".px",""));
            var duree_en_sec=(((height_css_value*2/30)/4)*60)*60;
            var duree_en_millisec=Math.round(duree_en_sec*1000);
            var fin_en_sec=depart_en_sec + duree_en_sec;
            var fin_en_millisec=depart_en_millisec + duree_en_millisec;
            nouvelle_heure_depart = new Date();
            nouvelle_heure_depart.setTime(depart_en_millisec);
            nouvelle_heure_fin = new Date();
            nouvelle_heure_fin.setTime(fin_en_millisec);
            $("#"+id_event+"_date_debut_heure").html(((nouvelle_heure_depart.getHours()<10?'0':'') + nouvelle_heure_depart.getHours()));
            $("#"+id_event+"_date_debut_minute").html(((nouvelle_heure_depart.getMinutes()<10?'0':'') + nouvelle_heure_depart.getMinutes()));
            $("#"+id_event+"_date_fin_heure").html(((nouvelle_heure_fin.getHours()<10?'0':'') + nouvelle_heure_fin.getHours()));
            $("#"+id_event+"_date_fin_minute").html(((nouvelle_heure_fin.getMinutes()<10?'0':'') + nouvelle_heure_fin.getMinutes()));
        },
        stop: function(event, ui) {
			//Repositionnement de l'élément
            var object_drop = $(this);
            var object_position=object_drop.position();
            var this_position=$(this).parent().position();
            current_position=object_position.top - this_position.top - 1;
            //placement de l'evenement
			var id_event=object_drop.attr("id");
            var marg_css=object_drop.css("margin-top");
            var marg_css_value=parseInt(marg_css.replace(".px",""));
            var margin_top=marg_css_value+current_position;
            if(margin_top<0)margin_top=0;
            margin_top=parseInt(margin_top);
            var depart_en_sec=(((margin_top*2/30)/4-1)*60)*60;
            var depart_en_millisec=Math.round(depart_en_sec*1000);
            var height_css=object_drop.css("height");
            var height_css_value=parseInt(height_css.replace(".px",""));
            var duree_en_sec=(((height_css_value*2/30)/4)*60)*60;
            var duree_en_millisec=Math.round(duree_en_sec*1000);
            var fin_en_sec=depart_en_sec + duree_en_sec;
            var fin_en_millisec=depart_en_millisec + duree_en_millisec;
            nouvelle_heure_depart = new Date();
            nouvelle_heure_depart.setTime(depart_en_millisec);
            nouvelle_heure_fin = new Date();
            nouvelle_heure_fin.setTime(fin_en_millisec);
            $("#"+id_event+"_date_debut_heure").html(((nouvelle_heure_depart.getHours()<10?'0':'') + nouvelle_heure_depart.getHours()));
            $("#"+id_event+"_date_debut_minute").html(((nouvelle_heure_depart.getMinutes()<10?'0':'') + nouvelle_heure_depart.getMinutes()));
            $("#"+id_event+"_date_fin_heure").html(((nouvelle_heure_fin.getHours()<10?'0':'') + nouvelle_heure_fin.getHours()));
            $("#"+id_event+"_date_fin_minute").html(((nouvelle_heure_fin.getMinutes()<10?'0':'') + nouvelle_heure_fin.getMinutes()));
			
			var heureDuDebut=((nouvelle_heure_depart.getHours()<10?'0':'') + nouvelle_heure_depart.getHours())+":"+((nouvelle_heure_depart.getMinutes()<10?'0':'') + nouvelle_heure_depart.getMinutes());
			var heureDeFin=((nouvelle_heure_fin.getHours()<10?'0':'') + nouvelle_heure_fin.getHours())+":"+((nouvelle_heure_fin.getMinutes()<10?'0':'') + nouvelle_heure_fin.getMinutes());
			
			//changement affichage horaire
			var dateTD= object_drop.parents("td").attr("id");
			//VERIFICATION SI PAS DEJA UN PLANNING SUR CE CRENEAU
			secondeDebut = (nouvelle_heure_depart.getHours() * 60) + nouvelle_heure_depart.getMinutes();
			secondeFin =  (nouvelle_heure_fin.getHours() * 60) + nouvelle_heure_fin.getMinutes();
			var modifAFaire=true;
			for(i=0;i<ListePlanning.length;i++){
				if (ListePlanning[i][0]!=id_event && ListePlanning[i][1]==dateTD){
					timeArrive = ListePlanning[i][2].split(':');
					newSecondeDebut = (timeArrive[0] * 60)+(timeArrive[1]*1);
					timeArrive = ListePlanning[i][3].split(':');
					newSecondeFin = (timeArrive[0] * 60)+(timeArrive[1]*1);
					if(newSecondeDebut<secondeFin && newSecondeFin>secondeDebut){
						if(document.getElementById('langue').value=="EN"){
							alert("Impossible, this time slot is already used");
						}
						else{
							alert("Impossible, ce cr\351neau est d\351j\340 utilis\351");
						}
						//Rechargement de la page
						modifAFaire=false;
						if(document.getElementById('pagePHP').value=="Planning.php"){
							window.parent.location=document.getElementById('pagePHP').value+"?laDate="+document.getElementById('laDateEC').value;
						}
						else{
							location.reload();
						}
					}
				}
			}
			//Modifier dans la base les heures
			if(modifAFaire==true){
				$.ajax({
					url : 'ModifHeureEvent.php',
					data : 'Id='+id_event+'&heureDebut='+heureDuDebut+'&heureFin='+heureDeFin,
					async : false,
				});
			}
        }
    });
	
    /* Redimensionnement event */
    $(".calendar_event").resizable({
        handles: 's',
        grid: [0, 5],
        stop: function(event, ui) {
            var object_drop = $(this);
            var id_event=object_drop.attr("id");
            var height_css=object_drop.css("height");
            var height_css_value=parseInt(height_css.replace(".px",""));
            var duree_en_sec=(((height_css_value*2/30)/4)*60)*60;
			var marg_css=object_drop.css("margin-top");
            var marg_css_value=parseInt(marg_css.replace(".px",""));
            var margin_top=marg_css_value;
            if(margin_top<0)margin_top=0;
            margin_top=parseInt(margin_top);
			
			var depart_en_sec=(((margin_top*2/30)/4-1)*60)*60;
            var depart_en_millisec=Math.round(depart_en_sec*1000);
			var heure_depart=parseInt($("#"+id_event+"_date_debut_heure").html());
            var min_depart=parseInt($("#"+id_event+"_date_debut_minute").html());  
            var heure_ref = new Date();
            heure_ref.setHours(heure_depart, min_depart, 0, 0);
            var timestamp=heure_ref.getTime();
            var height_css=object_drop.css("height");
            var height_css_value=parseInt(height_css.replace(".px",""));
            var duree_en_milli=((((height_css_value*2/30)/4)*60)*60)*1000;
			nouvelle_heure_depart = new Date();
            nouvelle_heure_depart.setTime(depart_en_millisec);
			nouvelle_heure_fin = new Date();
			nouvelle_heure_fin.setTime(timestamp+duree_en_milli);
		
			var heureDeFin=((nouvelle_heure_fin.getHours()<10?'0':'') + nouvelle_heure_fin.getHours())+":"+((nouvelle_heure_fin.getMinutes()<10?'0':'') + nouvelle_heure_fin.getMinutes());
			
			var dateTD= object_drop.parents("td").attr("id");
			//VERIFICATION SI PAS DEJA UN PLANNING SUR CE CRENEAU
			secondeDebut = (nouvelle_heure_depart.getHours() * 60) + nouvelle_heure_depart.getMinutes();
			secondeFin =  (nouvelle_heure_fin.getHours() * 60) + nouvelle_heure_fin.getMinutes();
			var modifAFaire=true;
			for(i=0;i<ListePlanning.length;i++){
				if (ListePlanning[i][0]!=id_event && ListePlanning[i][1]==dateTD){
					timeArrive = ListePlanning[i][2].split(':');
					newSecondeDebut = (timeArrive[0] * 60)+(timeArrive[1]*1);
					timeArrive = ListePlanning[i][3].split(':');
					newSecondeFin = (timeArrive[0] * 60)+(timeArrive[1]*1);
					if(newSecondeDebut<secondeFin && newSecondeFin>secondeDebut){
						if(document.getElementById('langue').value=="EN"){
							alert("Impossible, this time slot is already used");
						}
						else{
							alert("Impossible, ce cr\351neau est d\351j\340 utilis\351");
						}
						//Rechargement de la page
						modifAFaire=false;
						if(document.getElementById('pagePHP').value=="Planning.php"){
							window.parent.location=document.getElementById('pagePHP').value+"?laDate="+document.getElementById('laDateEC').value;
						}
						else{
							location.reload();
						}
					}
				}
			}
			
			//Modifier dans la base les heures
			if(modifAFaire==true){
				$.ajax({
					url : 'ModifHeureEvent.php',
					data : 'Id='+id_event+'&heureDebut=-1&heureFin='+heureDeFin,
					async : false,
				});
			}
        },
        resize: function(event,ui){
            var object_drop = $(this);
            var id_event=object_drop.attr("id");   
            var heure_depart=parseInt($("#"+id_event+"_date_debut_heure").html());
            var min_depart=parseInt($("#"+id_event+"_date_debut_minute").html());  
            var heure_ref = new Date();
            heure_ref.setHours(heure_depart, min_depart, 0, 0);
            var timestamp=heure_ref.getTime();
            var height_css=object_drop.css("height");
            var height_css_value=parseInt(height_css.replace(".px",""));
            var duree_en_milli=Math.round(((((height_css_value*2/30)/4)*60)*60)*1000);
    
            var new_heure = new Date();
            new_heure.setTime(timestamp+duree_en_milli);
    
            $("#"+id_event+"_date_fin_heure").html(((new_heure.getHours()<10?'0':'') + new_heure.getHours()));
            $("#"+id_event+"_date_fin_minute").html(((new_heure.getMinutes()<10?'0':'') + new_heure.getMinutes()));
        }
    });

    /*nouvel event*/
    $(".calendar_td").dblclick(function(e){
        var agenda_first_id=0;
        var position_choisie=e.pageY-$(this).position().top;

        var to_round=Math.round(position_choisie);
        var to_string=String(to_round);
        var str_length=to_string.length-1;
        var to_substr=to_string.substr(0,str_length);
        var to_int=parseInt(to_substr);
        if(!to_int){to_int=0;}
        var margin_top=to_int*10;
        var depart_en_sec=(((margin_top*2/30)/4-1)*60)*60;
        var depart_en_millisec=Math.round(depart_en_sec*1000);
        var height_css_value=parseInt(60);
        var duree_en_sec=(((height_css_value*2/30)/4)*60)*60;
        var duree_en_millisec=Math.round(duree_en_sec*1000);
        var fin_en_sec=depart_en_sec + duree_en_sec;
        var fin_en_millisec=depart_en_millisec + duree_en_millisec;
        nouvelle_heure_depart = new Date();
        nouvelle_heure_depart.setTime(depart_en_millisec);
        nouvelle_heure_fin = new Date();
        nouvelle_heure_fin.setTime(fin_en_millisec);
        var day_choose=($(this).attr("id"));

		var heureDuDebut=((nouvelle_heure_depart.getHours()<10?'0':'') + nouvelle_heure_depart.getHours())+":"+((nouvelle_heure_depart.getMinutes()<10?'0':'') + nouvelle_heure_depart.getMinutes());
		var heureDeFin=((nouvelle_heure_fin.getHours()<10?'0':'') + nouvelle_heure_fin.getHours())+":"+((nouvelle_heure_fin.getMinutes()<10?'0':'') + nouvelle_heure_fin.getMinutes());
		var event_id=leId;
		leId = leId - 1;
		
		//VERIFICATION SI PAS DEJA UN PLANNING SUR CE CRENEAU
		secondeDebut = (nouvelle_heure_depart.getHours() * 60) + nouvelle_heure_depart.getMinutes();
		secondeFin =  (nouvelle_heure_fin.getHours() * 60) + nouvelle_heure_fin.getMinutes();
		debut="";
		for(i=0;i<ListePlanning.length;i++){
			if (ListePlanning[i][0]!=event_id && ListePlanning[i][1]==day_choose){
				timeArrive = ListePlanning[i][2].split(':');
				newSecondeDebut = (timeArrive[0] * 60)+(timeArrive[1]*1);
				timeArrive = ListePlanning[i][3].split(':');
				newSecondeFin = (timeArrive[0] * 60)+(timeArrive[1]*1);
				if(newSecondeDebut<secondeFin && newSecondeFin>secondeDebut){
					debut=newSecondeDebut;
				}
				
			}
		}
		if(debut!=""){
			//Différence en minute entre debut et nouvelle_heure_depart
			height_css_value=parseInt(((debut-secondeDebut)*10)/5);
			duree_en_sec=(((height_css_value*2/30)/4)*60)*60;
			duree_en_millisec=Math.round(duree_en_sec*1000);
			fin_en_sec=depart_en_sec + duree_en_sec;
			fin_en_millisec=depart_en_millisec + duree_en_millisec;
			nouvelle_heure_depart = new Date();
			nouvelle_heure_depart.setTime(depart_en_millisec);
			nouvelle_heure_fin = new Date();
			nouvelle_heure_fin.setTime(fin_en_millisec);
			
			heureDeFin=((nouvelle_heure_fin.getHours()<10?'0':'') + nouvelle_heure_fin.getHours())+":"+((nouvelle_heure_fin.getMinutes()<10?'0':'') + nouvelle_heure_fin.getMinutes());
		}
        /*dialog de remplissage*/
        $("#gen_new_content").dialog({
            bgiframe: true,
            resizable: true,
            height:200,
            width:1000,
            modal: true,
            beforeclose: function(event, ui) {
                $(this).dialog('destroy');
                $("#new_event_tache").val("");
                $("#new_event_wp").val("");
				$("#new_event_commentaire").val("");
            },
			buttons: {
				'Enregistrer': function() {
					$(this).dialog('destroy');
					var new_presta=$("#new_event_presta").val();
					var new_titre=$("#new_event_tache").val();
					var new_lieu=$("#new_event_wp").val();
					var new_commentaire=$("#new_event_commentaire").val();
					agenda_id=$("#agenda_id").val();
					
					
					/*creation de l'event dans la bdd*/
					var url_create="CreerEvent.php";
					$.ajax({
						url : url_create,
						data : 'Id_Tache='+new_titre+'&Id_WP='+new_lieu+'&laDate='+day_choose+'&heureDebut='+heureDuDebut+'&heureFin='+heureDeFin+'&commentaire='+new_commentaire+'&Id_Prepa='+document.getElementById('Id_Prepa').value+'&Id_Prestation='+new_presta,
						async:false,
					});
					//Rechargement de la page
					if(document.getElementById('pagePHP').value=="Planning.php"){
						window.parent.location=document.getElementById('pagePHP').value+"?laDate="+document.getElementById('laDateEC').value;
					}
					else{
						location.reload();
					}
					if(new_titre!=""){
						$("#"+event_id+'_tache').html(new_titre);
					}
					if(new_lieu!=""){
						$("#"+event_id+'_wp').html(new_lieu);
					}
					if(new_commentaire!=""){
						$("#"+event_id+'_commentaire').html(new_commentaire);
					}
					new_ti=new_titre.replace(/ /gi,"&nbsp;");
					new_li=new_lieu.replace(/ /gi,"&nbsp;");
					new_co=new_commentaire.replace(/ /gi,"&nbsp;");
					$("#"+event_id).removeClass("select_agenda_red");
					class_color_agenda=$("#"+agenda_id+"_agenda_id").html();
					$("#"+event_id).addClass(class_color_agenda);
					$("#new_event_presta").val("");
					$("#new_event_tache").val("");
					$("#new_event_wp").val("");
					$("#new_event_commentaire").val("");
				},
				'Annuler': function() {
					$(this).dialog('destroy');
					$("#"+event_id).hide("highlight",{
						direction: "vertical",
						color: "#A60000"
					},1000);
					$("#new_event_presta").val("");
					$("#new_event_tache").val("");
					$("#new_event_wp").val("");
					$("#new_event_commentaire").val("");
				},
			},
        });
		
		if(document.getElementById('langue').value=="EN"){
			$(":button:contains('Annuler')").html("Cancel") ;
			$(":button:contains('Enregistrer')").html("Save") ;
		}

        var event = $('<div></div>')
        .appendTo($(this))
        .attr('class','calendar_event select_agenda_blue')
        .attr('id',event_id)
        .css({
            height:height_css_value+"px",
            marginTop:margin_top+"px"
        });

        var event_date = $('<div></div>')
        .appendTo(event)
        .attr('class','calendar_event_date')
        .attr('div',event_id+'_calendar_event_date');

        var event_date_heure_debut = $('<span>'+((nouvelle_heure_depart.getHours()<10?'0':'') + nouvelle_heure_depart.getHours())+'</span>')
        .appendTo(event_date)
        .attr('id',event_id+'_date_debut_heure');

        $('<span>:</span>')
        .appendTo(event_date);

        var event_date_minute_debut = $('<span>'+((nouvelle_heure_depart.getMinutes()<10?'0':'') + nouvelle_heure_depart.getMinutes())+'</span>')
        .appendTo(event_date)
        .attr('id',event_id+'_date_debut_minute');

        $('<span> - </span>')
        .appendTo(event_date);

        var event_date_heure_fin = $('<span>'+((nouvelle_heure_fin.getHours()<10?'0':'') + nouvelle_heure_fin.getHours())+'</span>')
        .appendTo(event_date)
        .attr('id',event_id+'_date_fin_heure');

        $('<span>:</span>')
        .appendTo(event_date);
        
        var event_date_heure_fin = $('<span>'+((nouvelle_heure_fin.getMinutes()<10?'0':'') + nouvelle_heure_fin.getMinutes())+'</span>')
        .appendTo(event_date)
        .attr('id',event_id+'_date_fin_minute');

        var event_date = $('<div>(Sans titre)</div>')
        .appendTo(event)
        .attr('class','calendar_event_title')
        .attr('id',event_id+'_title');

        var event_date = $('<div>(Inconnu)</div>')
        .appendTo(event)
        .attr('class','calendar_event_lieu')
        .attr('id',event_id+'_lieu');

        event.corner();
        $(".calendar_event_date").corner("top cc:#fff");
        var td_width=$(".calendar_td").width();
        event.css({
            "width" : td_width*0.98,
            "margin-left" : (td_width-(td_width*0.98))/2
        });
        

        
        /*application des interactions avec l'event*/
        event.draggable({
            containment: "parent",
            grid: [1, 5],
            delay: 100,
            drag: function(event, ui) {
                var object_drop = $(this);
                var object_position=object_drop.position();
                var this_position=$(this).parent().position();
                current_position=object_position.top - this_position.top - 1;

                /*placement de l'evenement*/
                var marg_css=object_drop.css("margin-top");
                var marg_css_value=parseInt(marg_css.replace(".px",""));
                var margin_top=marg_css_value+current_position;
                if(margin_top<0)margin_top=0;
                margin_top=parseInt(margin_top);



                /*changement affichage horaire*/
                var id_event=object_drop.attr("id");

                var depart_en_sec=(((margin_top*2/30)/4-1)*60)*60;
                var depart_en_millisec=Math.round(depart_en_sec*1000);
                var height_css=object_drop.css("height");
                var height_css_value=parseInt(height_css.replace(".px",""));
                var duree_en_sec=(((height_css_value*2/30)/4)*60)*60;
                var duree_en_millisec=Math.round(duree_en_sec*1000);
                var fin_en_sec=depart_en_sec + duree_en_sec;
                var fin_en_millisec=depart_en_millisec + duree_en_millisec;
                nouvelle_heure_depart = new Date();
                nouvelle_heure_depart.setTime(depart_en_millisec);
                nouvelle_heure_fin = new Date();
                nouvelle_heure_fin.setTime(fin_en_millisec);
                $("#"+id_event+"_date_debut_heure").html(((nouvelle_heure_depart.getHours()<10?'0':'') + nouvelle_heure_depart.getHours()));
                $("#"+id_event+"_date_debut_minute").html(((nouvelle_heure_depart.getMinutes()<10?'0':'') + nouvelle_heure_depart.getMinutes()));
                $("#"+id_event+"_date_fin_heure").html(((nouvelle_heure_fin.getHours()<10?'0':'') + nouvelle_heure_fin.getHours()));
                $("#"+id_event+"_date_fin_minute").html(((nouvelle_heure_fin.getMinutes()<10?'0':'') + nouvelle_heure_fin.getMinutes()));
            },
            stop: function(event, ui) {
                var object_drop = $(this);
                var object_position=object_drop.position();
                var this_position=$(this).parent().position();
                current_position=object_position.top - this_position.top - 1;

                /*placement de l'evenement*/
                var marg_css=object_drop.css("margin-top");
                var marg_css_value=parseInt(marg_css.replace(".px",""));
                var margin_top=marg_css_value+current_position;
                if(margin_top<0)margin_top=0;
                margin_top=parseInt(margin_top);



                /*changement affichage horaire*/
                var id_event=object_drop.attr("id");

                var depart_en_sec=(((margin_top*2/30)/4-1)*60)*60;
                var depart_en_millisec=Math.round(depart_en_sec*1000);
                var height_css=object_drop.css("height");
                var height_css_value=parseInt(height_css.replace(".px",""));
                var duree_en_sec=(((height_css_value*2/30)/4)*60)*60;
                var duree_en_millisec=Math.round(duree_en_sec*1000);
                var fin_en_sec=depart_en_sec + duree_en_sec;
                var fin_en_millisec=depart_en_millisec + duree_en_millisec;
                nouvelle_heure_depart = new Date();
                nouvelle_heure_depart.setTime(depart_en_millisec);
                nouvelle_heure_fin = new Date();
                nouvelle_heure_fin.setTime(fin_en_millisec);
                $("#"+id_event+"_date_debut_heure").html(((nouvelle_heure_depart.getHours()<10?'0':'') + nouvelle_heure_depart.getHours()));
                $("#"+id_event+"_date_debut_minute").html(((nouvelle_heure_depart.getMinutes()<10?'0':'') + nouvelle_heure_depart.getMinutes()));
                $("#"+id_event+"_date_fin_heure").html(((nouvelle_heure_fin.getHours()<10?'0':'') + nouvelle_heure_fin.getHours()));
                $("#"+id_event+"_date_fin_minute").html(((nouvelle_heure_fin.getMinutes()<10?'0':'') + nouvelle_heure_fin.getMinutes()));
            }
        });
        event.resizable({
            handles: 's',
            grid: [0, 5],
            stop: function(event, ui) {
                var object_drop = $(this);
                var id_event=object_drop.attr("id");
                var height_css=object_drop.css("height");
                var height_css_value=parseInt(height_css.replace(".px",""));
                var duree_en_sec=(((height_css_value*2/30)/4)*60)*60;
            },
            resize: function(event,ui) {
                var object_drop = $(this);
                var id_event=object_drop.attr("id");

                var heure_depart=parseInt($("#"+id_event+"_date_debut_heure").html());
                var min_depart=parseInt($("#"+id_event+"_date_debut_minute").html());

                var heure_ref = new Date();
                heure_ref.setHours(heure_depart, min_depart, 0, 0);

                var timestamp=heure_ref.getTime();




                var height_css=object_drop.css("height");
                var height_css_value=parseInt(height_css.replace(".px",""));
                var duree_en_milli=Math.round(((((height_css_value*2/30)/4)*60)*60)*1000);

                var new_heure = new Date();
                new_heure.setTime(timestamp+duree_en_milli);

                $("#"+id_event+"_date_fin_heure").html(((new_heure.getHours()<10?'0':'') + new_heure.getHours()));
                $("#"+id_event+"_date_fin_minute").html(((new_heure.getMinutes()<10?'0':'') + new_heure.getMinutes()));
            }
        });
        event.click(function(e){
            var object_clicked = $(this);
            var id_event=object_clicked.attr("id");

            $("#dialog").dialog({
                bgiframe: true,
                resizable: true,
                height:140,
                width:500,
                modal: true,
                overlay: {
                    backgroundColor: '#000',
                    opacity: 0.5
                },
                beforeclose: function(event, ui) {
                    $(this).dialog('destroy');
                },
                open: function(event, ui) {
                    var heure_depart=$("#"+id_event+"_date_debut_heure").html();
                    var min_depart=$("#"+id_event+"_date_debut_minute").html();
                    var heure_fin=$("#"+id_event+"_date_fin_heure").html();
                    var min_fin=$("#"+id_event+"_date_fin_minute").html();
                    var titre_eve=day_choose+"   "+heure_depart+":"+min_depart+" - "+heure_fin+":"+min_fin;
					var lieu_eve=$("#"+id_event+"_commentaire").html();

					var contenu = "<table>";
					if(document.getElementById('langue').value=="EN"){
						contenu += "<tr><td><label class=\"label_presta\" for=\"new_event_wp2\">Activity</label></td>";
					}
					else{
						contenu += "<tr><td><label class=\"label_presta\" for=\"new_event_wp2\">Prestation</label></td>";
					}
					var i;
					var isElement = false;
					contenu +="<td><select class='lab' name='new_event_presta2' id='new_event_presta2' onchange=\"RechargerWP2('"+document.getElementById('langue').value+"')\">";
					for(i=0;i<Liste_Presta.length;i++){
						$selected="";
						
						if($("#"+id_event+"_presta").html()==Liste_Presta[i][0]){$selected="selected";}
						contenu += "<option value='"+Liste_Presta[i][0]+"' "+$selected+">"+Liste_Presta[i][1]+"</option>";
						isElement = true;
					}
					if(isElement == false){contenu += "<option value='0' selected></option>";}
					contenu += "</select></td></tr><tr><td height=\"4\"></td></tr>";
					if(document.getElementById('langue').value=="EN"){
						contenu += "<tr><td><label class=\"label_wp\" for=\"new_event_wp2\">Workpackage</label></td>";
					}
					else{
						contenu += "<tr><td><label class=\"label_wp\" for=\"new_event_wp2\">Workpackage</label></td>";
					}
					var i;
					var isElement = false;
					contenu +="<td><div id='divWP2'><select class='lab' name='new_event_wp2' id='new_event_wp2' onchange=\"RechargerTache2('"+document.getElementById('langue').value+"')\">";
					for(i=0;i<Liste_WP.length;i++){
						$selected="";
						if($("#"+id_event+"_wp").html()==Liste_WP[i][0]){$selected="selected";}
						contenu += "<option value='"+Liste_WP[i][0]+"' "+$selected+">"+Liste_WP[i][2]+"</option>";
						isElement = true;
					}
					if(isElement == false){contenu += "<option value='0' selected></option>";}
					contenu += "</select></div></td></tr><tr><td height=\"4\"></td></tr>";
					if(document.getElementById('langue').value=="EN"){
						contenu += "<tr><td><label class=\"label_wp\" for=\"new_event_wp\">Task</label></td>";
					}
					else{
						contenu += "<tr><td><label class=\"label_wp\" for=\"new_event_wp\">T&#226;che</label></td>";
					}
					isElement = false;
					contenu +="<td><div id='divTache2'><select class='lab' name='new_event_tache2' id='new_event_tache2'>";
					for(i=0;i<Liste_Tache_WP.length;i++){
						if (Liste_Tache_WP[i][1]==$("#"+id_event+"_wp").html() && Liste_Tache_WP[i][2]=="0"){
							
							$selected="";
							if($("#"+id_event+"_tache").html()==Liste_Tache_WP[i][0]){$selected="selected";}
							contenu += "<option value='"+Liste_Tache_WP[i][0]+ "' "+$selected+">"+Liste_Tache_WP[i][3]+"</option>";
							
							isElement = true;
						}
					}
					if(isElement == false){contenu += "<option value='0' selected></option>";}
					contenu += "</select></div></td></tr><tr><td height=\"4\"></td></tr>";
					if(document.getElementById('langue').value=="EN"){
						contenu += "<tr><td><label class=\"label_wp\" for=\"new_event_wp\">Comment</label></td>";
					}
					else{
						contenu += "<tr><td><label class=\"label_wp\" for=\"new_event_wp\">Commentaire</label></td>";
					}
					contenu += "<td><input type=\"text\" class=\"lab\" size=\"110px\" name=\"new_event_commentaire2\" id=\"new_event_commentaire2\" value=\""+lieu_eve+"\"/></td></tr>";
					contenu += "</table>";
                    $("#ui-dialog-title-dialog").html(titre_eve);
                    $("#dialog").html(contenu);
                },
                buttons: {
					'Annuler': function() {
						$(this).dialog('destroy');
					},
                    'Modifier': function() {
                        $(this).dialog('destroy');
						
						var new_titre=$("#new_event_tache2").val();
						var new_lieu=$("#new_event_wp2").val();
						var new_prestation=$("#new_event_presta2").val();
						var new_commentaire=$("#new_event_commentaire2").val();
						
						/*modifier l'event dans la bdd*/
						var url_create="ModifEvent.php";
						$.ajax({
							url : url_create,
							data : 'Id='+id_event+'&Id_Tache='+new_titre+'&Id_WP='+new_lieu+'&Id_Prestation='+new_prestation+'&Commentaire='+new_commentaire,
							async:false,
						});
						
						if(document.getElementById('pagePHP').value=="Planning.php"){
							window.parent.location=document.getElementById('pagePHP').value+"?laDate="+document.getElementById('laDateEC').value;
						}
						else{
							location.reload();
						}
                    },
                    'Supprimer': function() {
                        if(document.getElementById('langue').value=="EN"){
							$(this).html("Please confirm the suppress");
						}
						else{
							$(this).html("Veuillez confirmer la suppression");
						}
                        $(this).dialog('destroy');
                        $("#dialog").dialog({
                            bgiframe: true,
                            resizable: true,
                            height:140,
                            modal: true,
                            beforeclose: function(event, ui) {
                                $(this).dialog('destroy');
                            },
                            buttons: {
                                'Supprimer': function() {
                                    $(this).dialog('destroy');
                                    $("#"+id_event).hide("highlight",{
                                        direction: "vertical",
                                        color: "#A60000"
                                    },2000);
									/*suppression de l'event dans la bdd*/
									var url_create="SupprimerEvent.php";
									$.ajax({
										url : url_create,
										data : 'Id='+id_event,
										async:false,
									});
                                },
                                'Annuler': function() {
                                    $(this).dialog('destroy');
                                }
                            }
                        });
						if(document.getElementById('langue').value=="EN"){
							$(":button:contains('Annuler')").html("Cancel") ;
							$(":button:contains('Supprimer')").html("Suppress") ;
						}
                    }
                }

            });
			if(document.getElementById('langue').value=="EN"){
				$(":button:contains('Annuler')").html("Cancel") ;
				$(":button:contains('Supprimer')").html("Suppress") ;
				$(":button:contains('Modifier')").html("Edit") ;
			}
        });

    });



    /*info event*/
    $(".calendar_event").click(function(e){
        var object_clicked = $(this);
        var id_event=object_clicked.attr("id");

        $("#dialog").dialog({
            bgiframe: true,
            resizable: true,
            height:200,
            width:1000,
            modal: true,
            overlay: {
                backgroundColor: '#000',
                opacity: 0.5
            },
            beforeclose: function(event, ui) {
                $(this).dialog('destroy');
            },
            open: function(event, ui) {    
                var heure_depart=$("#"+id_event+"_date_debut_heure").html();
                var min_depart=$("#"+id_event+"_date_debut_minute").html();
                var heure_fin=$("#"+id_event+"_date_fin_heure").html();
                var min_fin=$("#"+id_event+"_date_fin_minute").html();
				var day_choose=$("#"+id_event+"_laDate").html();
                var titre_eve=day_choose+"   "+heure_depart+":"+min_depart+" - "+heure_fin+":"+min_fin;
                var lieu_eve=$("#"+id_event+"_commentaire").html();

                var contenu = "<table>";
				if(document.getElementById('langue').value=="EN"){
					contenu += "<tr><td><label class=\"label_presta\" for=\"new_event_wp2\">Activity</label></td>";
				}
				else{
					contenu += "<tr><td><label class=\"label_presta\" for=\"new_event_wp2\">Prestation</label></td>";
				}
				var i;
				var isElement = false;
				contenu +="<td><select class='lab' name='new_event_presta2' id='new_event_presta2' onchange=\"RechargerWP2('"+document.getElementById('langue').value+"')\">";
				for(i=0;i<Liste_Presta.length;i++){
					$selected="";
					if($("#"+id_event+"_presta").html()==Liste_Presta[i][0]){$selected="selected";}
					contenu += "<option value='"+Liste_Presta[i][0]+"' "+$selected+">"+Liste_Presta[i][1]+"</option>";
					isElement = true;
				}
				if(isElement == false){contenu += "<option value='0' selected></option>";}
					contenu += "</select></td></tr><tr><td height=\"4\"></td></tr>";
				if(document.getElementById('langue').value=="EN"){
					contenu += "<tr><td><label class=\"label_wp\" for=\"new_event_wp2\">Workpackage</label></td>";
				}
				else{
					contenu += "<tr><td><label class=\"label_wp\" for=\"new_event_wp2\">Workpackage</label></td>";
				}
				var i;
				var isElement = false;
				contenu +="<td><div id='divWP2'><select class='lab' name='new_event_wp2' id='new_event_wp2' onchange=\"RechargerTache2('"+document.getElementById('langue').value+"')\">";
				for(i=0;i<Liste_WP.length;i++){
					$selected="";
					if($("#"+id_event+"_wp").html()==Liste_WP[i][0]){$selected="selected";}
					contenu += "<option value='"+Liste_WP[i][0]+"' "+$selected+">"+Liste_WP[i][2]+"</option>";
					isElement = true;
				}
				if(isElement == false){contenu += "<option value='0' selected></option>";}
				contenu += "</select></div></td></tr><tr><td height=\"4\"></td></tr>";
				if(document.getElementById('langue').value=="EN"){
					contenu += "<tr><td><label class=\"label_wp\" for=\"new_event_wp\">Task</label></td>";
				}
				else{
					contenu += "<tr><td><label class=\"label_wp\" for=\"new_event_wp\">T&#226;che</label></td>";
				}
				isElement = false;
				contenu +="<td><div id='divTache2'><select class='lab' name='new_event_tache2' id='new_event_tache2'>";
				for(i=0;i<Liste_Tache_WP.length;i++){
					if (Liste_Tache_WP[i][1]==$("#"+id_event+"_wp").html() && Liste_Tache_WP[i][2]=="0"){
						
						$selected="";
						if($("#"+id_event+"_tache").html()==Liste_Tache_WP[i][0]){$selected="selected";}
						contenu += "<option value='"+Liste_Tache_WP[i][0]+ "' "+$selected+">"+Liste_Tache_WP[i][3]+"</option>";
						
						isElement = true;
					}
				}
				if(isElement == false){contenu += "<option value='0' selected></option>";}
				contenu += "</select></div></td></tr><tr><td height=\"4\"></td></tr>";
				if(document.getElementById('langue').value=="EN"){
					contenu += "<tr><td><label class=\"label_wp\" for=\"new_event_wp\">Comment</label></td>";
				}
				else{
					contenu += "<tr><td><label class=\"label_wp\" for=\"new_event_wp\">Commentaire</label></td>";
				}
				contenu += "<td><input type=\"text\" class=\"lab\" size=\"110px\" name=\"new_event_commentaire2\" id=\"new_event_commentaire2\" value=\""+lieu_eve+"\"/></td></tr>";
                contenu += "</table>";
                $("#ui-dialog-title-dialog").html(titre_eve);             
                $("#dialog").html(contenu);
            },
            buttons: {
				'Annuler': function() {
                    $(this).dialog('destroy');
                },
                'Modifier': function() {
                    $(this).dialog('destroy');
					
					var new_titre=$("#new_event_tache2").val();
					var new_lieu=$("#new_event_wp2").val();
					var new_prestation=$("#new_event_presta2").val();
					var new_commentaire=$("#new_event_commentaire2").val();
					/*modifier l'event dans la bdd*/
					var url_create="ModifEvent.php";
					$.ajax({
						url : url_create,
						data : 'Id='+id_event+'&Id_Tache='+new_titre+'&Id_WP='+new_lieu+'&Id_Prestation='+new_prestation+'&Commentaire='+new_commentaire,
						async:false,
					});
					if(document.getElementById('pagePHP').value=="Planning.php"){
						window.parent.location=document.getElementById('pagePHP').value+"?laDate="+document.getElementById('laDateEC').value;
					}
					else{
						location.reload();
					}
                },
                'Supprimer': function() {
					if(document.getElementById('langue').value=="EN"){
						$(this).html("Please confirm the suppress");
					}
					else{
						$(this).html("Veuillez confirmer la suppression");
					}
					var ok=true;
                    $("#dialog").dialog('destroy');
                    $("#dialog").dialog({
                        bgiframe: true,
                        resizable: true,
                        height:140,
                        modal: true,
                        beforeclose: function(event, ui) {
                            $(this).dialog('destroy');
                        },
                        buttons: {
                            'Supprimer': function() {
                                $(this).dialog('destroy');
                                $("#"+id_event).hide("highlight",{
                                    direction: "vertical",
                                    color: "#A60000"
                                },2000);
								/*suppression de l'event dans la bdd*/
								var url_create="SupprimerEvent.php";
								$.ajax({
									url : url_create,
									data : 'Id='+id_event,
									async:false,
								});

                            },
                            'Annuler': function() {
                                $(this).dialog('destroy');
                            }
                        }
                    });
					if(document.getElementById('langue').value=="EN"){
						$(":button:contains('Annuler')").html("Cancel") ;
						$(":button:contains('Supprimer')").html("Suppress") ;
					}
                }
            }

        });
		if(document.getElementById('langue').value=="EN"){
			$(":button:contains('Annuler')").html("Cancel") ;
			$(":button:contains('Supprimer')").html("Suppress") ;
			$(":button:contains('Modifier')").html("Edit") ;
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
	$('.calendar2_event').corner("cc:#fff");
    $('.calendar2_event_date').corner("top cc:#fff");
	$('.calendarAutre_event').corner("cc:#fff");
    $('.calendarAutre_event_date').corner("top cc:#fff");
    $(".switcher_agenda_inside").corner("top");
    $("div#select_agenda").corner("top");
    $(".select_agenda_selector").corner("round 4px");
});
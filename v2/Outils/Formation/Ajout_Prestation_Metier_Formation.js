var estConfirmé = false;
function Excel()
{
	var w=window.open("Excel_FormationMetier.php?Id_Prestation="+document.getElementById('Id_Prestation').value+"&Id_Plateforme="+document.getElementById('Id_Plateforme').value,"PageExcel","status=no,menubar=no,scrollbars=yes,width=90,height=90");
	w.focus();
}

function CocheTousMetiers()
{
	for(var j=0; j<document.getElementsByClassName('CopiePresta_MetierCheck').length; j++)
	{
		document.getElementsByClassName('CopiePresta_MetierCheck')[j].checked=document.getElementById('TousMetiers').checked;
	}
}

function AffichageChoix()
{
	var Affichage_Choix = document.getElementsByName('Affichage_Choix');
	var Code="";
	
	document.getElementById('Type_Titre').style.visibility="hidden";
	document.getElementById('Obligatoire').style.visibility="hidden";
	document.getElementById('Formation_Titre').style.visibility="hidden";
	document.getElementById('Div_Formation').style.visibility="hidden";
	document.getElementById('Div_Formation').style.height="0px";
	
	for (var j=0; j<Affichage_Choix.length; j++)
	{
		if (Affichage_Choix[j].checked)
		{
			if(Affichage_Choix[j].value==1)
			{
				document.getElementById('Type_Titre').style.visibility="visible";
				document.getElementById('Obligatoire').style.visibility="visible";
				document.getElementById('Formation_Titre').style.visibility="visible";
				document.getElementById('Div_Formation').style.height="250px";
				document.getElementById('Div_Formation').style.visibility="visible";
				
				Code="<table width='100%'>";
				Code+="<tr><td class='Libelle' width='100%'>";
				if(document.getElementById('Langue').value=="FR"){Code+="Cocher les métiers : ";}
				else{Code+="For this job : ";}
				Code+="</td></tr>";
				for(i=0;i<ListeMetier.length;i++)
				{
					Code+="<tr><td align='left' colspan='2'><input type='checkbox' class='checkMetiers' name='Id_Metiers[]' Id='Id_Metiers[]' value='"+ListeMetier[i][0]+"' >"+ListeMetier[i][1]+"<td></tr>";
				}
				Code+="</table>";
				document.getElementById('Radio_Choix').innerHTML=Code;
			}
			else if(Affichage_Choix[j].value==2)
			{
				Code="<table>";
				Code+="<tr><td class='Libelle' width='30%'>";
				if(document.getElementById('Langue').value=="FR"){Code+="Pour ce métier : ";}
				else{Code+="For this job : ";}
				Code+="</td></tr>";
				Code+="<tr><td align='left'>";
				Code+="<select name='Id_Metier' Id='Id_Metier' style='width:280px' onchange='DecocherFormations();'>";
				Code+="<option value='' selected></option>";
				for(i=0;i<ListeMetier.length;i++)
				{
					Code+="<option value='"+ListeMetier[i][0]+"'>"+ListeMetier[i][1]+"</option>";
				}
				Code+="</select>";
				Code+="<td></tr>";
				
				Code+="<tr><td class='Libelle' width='30%'>";
				if(document.getElementById('Langue').value=="FR"){Code+="A partir de ce métier : ";}
				else{Code+="From this job : ";}
				Code+="</td></tr>";
				Code+="<tr><td align='left'>";
				Code+="<select name='Id_MetierCopie' Id='Id_MetierCopie' style='width:280px'>";
				Code+="<option value='' selected></option>";
				for(i=0;i<ListeMetier.length;i++)
				{
					Code+="<option value='"+ListeMetier[i][0]+"'>"+ListeMetier[i][1]+"</option>";
				}
				Code+="</select>";
				Code+="<td></tr>";
				Code+="</table>";
				document.getElementById('Radio_Choix').innerHTML=Code;
			}
			else if(Affichage_Choix[j].value==3)
			{
				Code="<table>";
				Code+="<tr><td class='Libelle' width='30%'>";
				if(document.getElementById('Langue').value=="FR"){Code+="De cette prestation : ";}
				else{Code+="From this activity : ";}
				Code+="</td></tr>";
				Code+="<tr><td align='left'>";
				Code+="<select id='Id_PrestationCopie' name='Id_PrestationCopie' style='width:280px'>";
				Code+="<option value='' selected></option>";
				for(i=0;i<ListePrestation.length;i++)
				{
					if(ListePrestation[i][1]==document.getElementById('Id_Plateforme').value)
					{
						Code+="<option value='"+ListePrestation[i][0]+"' "+selected+">"+ListePrestation[i][2]+"</option>";
					}
				}
				Code+="</select>";
				Code+=" <b>&nbsp;All</b> <input type='checkbox' id='TousMetiers' onclick='CocheTousMetiers();'>";
				Code+="<td></tr>";
				Code+="<tr><td width='100%'>";
				Code+="<div id='Div_Formation' style='height:300px;overflow:auto;'>";
				Code+="<table style='border-spacing:0;'>";
				for(i=0;i<ListeMetier.length;i+=2)
				{
					Code+="<tr>";
					Code+="<td width='50%'><input class='CopiePresta_MetierCheck' type='checkbox' name='"+ListeMetier[i][0]+"' value='"+ListeMetier[i][0]+"'>"+ListeMetier[i][1]+"</td>";
					if(i+1<ListeMetier.length)
					{
						Code+="<td width='50%'><input class='CopiePresta_MetierCheck' type='checkbox' name='"+ListeMetier[i+1][0]+"' value='"+ListeMetier[i+1][0]+"'>"+ListeMetier[i+1][1]+"</td>";
					}
					Code+="</tr>";
				}
				Code+="</table>";
				Code+="</div>";
				Code+="</td></tr>";
				Code+="</table>";
				document.getElementById('Radio_Choix').innerHTML=Code;
			}
			break;
		}
	}
}

ListePrestation = new Array();

ListeFormation = new Array();
function RechargerListeFormation()
{
	var HTML_ListeFormation="";
	for(i=0;i<ListeFormation.length;i++)
	{
		if(ListeFormation[i][1]==document.getElementById('Id_Plateforme').value || ListeFormation[i][1]==0){
			if((document.getElementById('DroitAF').value==1 || ListeFormation[i][4]==0))
				{HTML_ListeFormation+="<div><input class='check' type='checkbox' id='"+ListeFormation[i][0]+"' name='"+ListeFormation[i][0]+"' value='"+ListeFormation[i][0]+"'>"+ListeFormation[i][2]+" ("+ListeFormation[i][3]+")</div>";}
			
		}
	}
	HTML_ListeFormation+="";
	document.getElementById('Div_Formation').innerHTML=HTML_ListeFormation;
	
	//FILTRE
	var HTML_ListeFormation="<select name='formation' id='formation' OnChange='RechargerListeMetierFormation();' style='width:280px'>";
	HTML_ListeFormation+="<option value='0'></option>";
	for(i=0;i<ListeFormation.length;i++)
	{
		if(ListeFormation[i][1]==document.getElementById('Id_Plateforme').value || ListeFormation[i][1]==0){
			selected="";
			if(document.getElementById('post_formation').value!=""){
				if(document.getElementById('post_formation').value==ListeFormation[i][0]){selected="selected";}
			}
			HTML_ListeFormation+="<option value='"+ListeFormation[i][0]+"' "+selected+">"+ListeFormation[i][2]+" ("+ListeFormation[i][3]+")</option>";
		}
	}
	if(document.getElementById('Langue').value=="FR"){
		document.getElementById('Div_Formations').innerHTML="Formation : "+HTML_ListeFormation;
	}
	else{
		document.getElementById('Div_Formations').innerHTML="Training : "+HTML_ListeFormation;
	}
	//Remise à zéro de la demande de confirmation
	estConfirmé = false;	
}

ListeMetierFormation = new Array();
ListeMetierFormation2 = new Array();
ListeMetier = new Array();
function RechargerListeMetierFormation(){	
	var HTML_TableauMetierFormation="<table id='tab_MetierFormation' class='TableCompetences' style='width:100%; border-spacing:0;'>";
	HTML_TableauMetierFormation+="<tr>";
	if(document.getElementById('Langue').value=="FR"){
		HTML_TableauMetierFormation+="<td class='EnTeteTableauCompetences' width='25%'>Métier</td>";
		HTML_TableauMetierFormation+="<td class='EnTeteTableauCompetences' width='65%'>Formation</td>";
		HTML_TableauMetierFormation+="<td class='EnTeteTableauCompetences' width='7%'>Obli./Facult.</td>";
	}
	else{
		HTML_TableauMetierFormation+="<td class='EnTeteTableauCompetences' width='25%'>Job</td>";
		HTML_TableauMetierFormation+="<td class='EnTeteTableauCompetences' width='65%'>Training</td>";
		HTML_TableauMetierFormation+="<td class='EnTeteTableauCompetences' width='7%'>Mandat./Opt.</td>";
	}
	HTML_TableauMetierFormation+="<td class='EnTeteTableauCompetences' width='3%'></td>";
	HTML_TableauMetierFormation+="</tr>";
	document.getElementById('lesMetiersFormations').value = "";
	document.getElementById('lesMetiersFormationsASuppr').value = "";
	for(i=0;i<ListeMetierFormation.length;i++){
		if(ListeMetierFormation[i][1]==document.getElementById('Id_Prestation').value){
			visible="style='display:none;'";
			if ((document.getElementById('metier').value==0 || ListeMetierFormation[i][5]==document.getElementById('metier').value) 
				&& (document.getElementById('formation').value==0 || ListeMetierFormation[i][6]==document.getElementById('formation').value)){
				visible="";
			}
			HTML_TableauMetierFormation+="<tr "+visible+" id='"+ListeMetierFormation[i][5]+"_"+ListeMetierFormation[i][4]+"_"+ListeMetierFormation[i][6]+"_MetierFormation'>";
				HTML_TableauMetierFormation+="<td style='border-bottom:1px dotted #000000'>"+ListeMetierFormation[i][2]+"</td>";
				HTML_TableauMetierFormation+="<td style='border-bottom:1px dotted #000000'>"+ListeMetierFormation[i][7]+" ("+ListeMetierFormation[i][3]+")</td>";
				if(document.getElementById('Langue').value=="FR"){
					if(ListeMetierFormation[i][4]==0){HTML_TableauMetierFormation+="<td style='border-bottom:1px dotted #000000'>Facultatif</td>";}else{HTML_TableauMetierFormation+="<td style='border-bottom:1px dotted #000000'>Obligatoire</td>";}
				}
				else{
					if(ListeMetierFormation[i][4]==0){HTML_TableauMetierFormation+="<td style='border-bottom:1px dotted #000000'>Optional</td>";}else{HTML_TableauMetierFormation+="<td style='border-bottom:1px dotted #000000'>Mandatory</td>";}
				}
				HTML_TableauMetierFormation+="<td style='border-bottom:1px dotted #000000'>";
				if (document.getElementById('bModif').value==1){
					if(document.getElementById('DroitFormation').value!="" && (document.getElementById('DroitAF').value==1 || ListeMetierFormation[i][9]==0) ){
						if(document.getElementById('Langue').value=="FR"){
							HTML_TableauMetierFormation+="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerMetierFormation('"+ListeMetierFormation[i][5]+"_"+ListeMetierFormation[i][4]+"_"+ListeMetierFormation[i][6]+"','"+ListeMetierFormation[i][8]+"')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
						}
						else{
							HTML_TableauMetierFormation+="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerMetierFormation('"+ListeMetierFormation[i][5]+"_"+ListeMetierFormation[i][4]+"_"+ListeMetierFormation[i][6]+"','"+ListeMetierFormation[i][8]+"')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Delete\" title=\"Delete\">&nbsp;&nbsp;</a>";
						}
					}
				}
				HTML_TableauMetierFormation+="</td>";
			HTML_TableauMetierFormation+="</tr>";
			document.getElementById('lesMetiersFormations').value = document.getElementById('lesMetiersFormations').value + ";" +ListeMetierFormation[i][5]+"_"+ListeMetierFormation[i][4]+"_"+ListeMetierFormation[i][6] + "_MetierFormation";
		}
	}
	HTML_TableauMetierFormation+="</table>";
	document.getElementById('Div_Tableau_Metier_Formation').innerHTML=HTML_TableauMetierFormation;
	document.getElementById('annuler').style.display="none";
	document.getElementById('save').style.display="none";
	
	//Remise à zéro de la demande de confirmation
	estConfirmé = false;	
}

function SupprimerMetierFormation(MetierQualif,nbSession){
	bValide=1;
	if(nbSession>0){
		if(document.getElementById('Langue').value=="FR"){
			question="Des personnes avec ce métier sont inscrites ou pré-inscrites à cette formation. Les inscriptions/pré-inscritions ne seront pas annulées. Etes-vous sûre de vouloir supprimer cette formation ? ";
		}
		else{
			question="People with this profession are registered or pre-registered for this training. Registrations / pre-registrations will not be canceled. Are you sure you want to delete this training?";
		}
		if(window.confirm(question)){
			bValide=1;
		}
		else{
			bValide=0;
		}
	}
	if(bValide==1){
		var row = document.getElementById(MetierQualif+"_MetierFormation");
		row.parentNode.removeChild(row);
		document.getElementById('lesMetiersFormations').value = document.getElementById('lesMetiersFormations').value.replace(";"+MetierQualif+"_MetierFormation","");
		document.getElementById('lesMetiersFormationsASuppr').value = document.getElementById('lesMetiersFormationsASuppr').value+";"+MetierQualif+"_MetierFormation";
		document.getElementById('annuler').style.display="";
		document.getElementById('save').style.display="";
	}
}

function SupprimerMetierFormationASuppr(MetierQualif){
	document.getElementById('lesMetiersFormationsASuppr').value = document.getElementById('lesMetiersFormationsASuppr').value.replace(";"+MetierQualif+"_MetierFormation","");
}

function AjouterMetierFormation()
{
	//Récupérer la liste des cases à cocher
	var elements = document.getElementsByClassName("check");
	var Elements_CopiePrestaMetierCheck = document.getElementsByClassName("CopiePresta_MetierCheck");
	var elementsMetier = document.getElementsByClassName("checkMetiers");
	var checked=true;
	var Choix=0;
	
	//Pour connaitre quel choix a été fait sur les boutons radios
	var Affichage_Choix = document.getElementsByName('Affichage_Choix');
	for (var j=0; j<Affichage_Choix.length; j++)
	{
		if (Affichage_Choix[j].checked)
		{
			Choix=Affichage_Choix[j].value;
			break;
		}
	}
	
	if(Choix==1 || Choix==2)
	{
		if(Choix==2)
		{
			if(document.getElementById('Id_Metier').value!="")
			{
				for(i=0;i<ListeMetierFormation.length;i++)
				{
					if(ListeMetierFormation[i][1]==document.getElementById('Id_Prestation').value && ListeMetierFormation[i][5]==document.getElementById('Id_MetierCopie').value)
					{
						if(document.getElementById('lesMetiersFormations').value.indexOf(";"+document.getElementById('Id_Metier').value+"_0_"+ListeMetierFormation[i][6]+"_MetierFormation")==-1 && document.getElementById('lesMetiersFormations').value.indexOf(";"+document.getElementById('Id_Metier').value+"_1_"+ListeMetierFormation[i][6]+"_MetierFormation")==-1)
						{
							document.getElementById('lesMetiersFormations').value = document.getElementById('lesMetiersFormations').value+";"+document.getElementById('Id_Metier').value+"_"+ListeMetierFormation[i][4]+"_"+ListeMetierFormation[i][6]+"_MetierFormation";
							var table = document.getElementById("tab_MetierFormation");
							var row = table.insertRow();
							row.id = document.getElementById('Id_Metier').value+"_"+ListeMetierFormation[i][4]+"_"+ListeMetierFormation[i][6]+"_MetierFormation";
							btn="";
							if(document.getElementById('DroitFormation').value!=""){
								if(document.getElementById('Langue').value=="FR")
								{
									btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerMetierFormation('"+document.getElementById('Id_Metier').value+"_"+ListeMetierFormation[i][4]+"_"+ListeMetierFormation[i][6]+"')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
								}
								else
								{
									btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerMetierFormation('"+document.getElementById('Id_Metier').value+"_"+ListeMetierFormation[i][4]+"_"+ListeMetierFormation[i][6]+"')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Delete\" title=\"Delete\">&nbsp;&nbsp;</a>";
								}
							}
							var Metier = "";
							var Formation = "";
							var Obligatoire = "";
							for(j=0;j<ListeMetier.length;j++)
							{
								if (ListeMetier[j][0]==document.getElementById('Id_Metier').value){Metier = ListeMetier[j][1];}
							}
							for(j=0;j<ListeFormation.length;j++)
							{
								if (ListeFormation[j][0]==ListeMetierFormation[i][6]){Formation = ListeFormation[j][2]+" ("+ListeFormation[j][3]+")";}
							}
							if((document.getElementById('metier').value==0 || document.getElementById('Id_Metier').value==document.getElementById('metier').value) && (document.getElementById('formation').value==0 || ListeMetierFormation[i][6]==document.getElementById('formation').value))
							{
								visible="";
							}
							else
							{
								row.style.display="none";
							}
							
							if(document.getElementById('Langue').value=="FR")
							{
								if(ListeMetierFormation[i][4]==1){Obligatoire="Obligatoire";}else{Obligatoire="Facultative";}
							}
							else
							{
								if(ListeMetierFormation[i][4]==1){Obligatoire="Mandatory";}else{Obligatoire="Optional";}
							}
							
							var cell = row.insertCell(0);
							cell.innerHTML = Metier;
							cell.style.borderBottom = "1px dotted #000000";
							var cell = row.insertCell(1);
							cell.innerHTML = Formation;
							cell.style.borderBottom = "1px dotted #000000";
							var cell = row.insertCell(2);
							cell.innerHTML = Obligatoire;
							cell.style.borderBottom = "1px dotted #000000";
							var cell = row.insertCell(3);
							cell.innerHTML = btn;
							cell.style.borderBottom = "1px dotted #000000";
							
							document.getElementById('annuler').style.display="";
							document.getElementById('save').style.display="";
							
							SupprimerMetierFormationASuppr(document.getElementById('Id_Metier').value+"_0_"+ListeMetierFormation[i][6]);
							SupprimerMetierFormationASuppr(document.getElementById('Id_Metier').value+"_1_"+ListeMetierFormation[i][6]);

						}
					}
				}
			}
		}
		else
		{
			if(elementsMetier.length>0)
			{
				for(var refmetier=0, nbMetier=elementsMetier.length; refmetier<nbMetier; refmetier++){
					if(elementsMetier[refmetier].checked==true){
						for(var c=0, l=elements.length; c<l; c++)
						{
							if(elements[c].checked==true)
							{
								if(document.getElementById('lesMetiersFormations').value.indexOf(";"+elementsMetier[refmetier].value+"_0_"+elements[c].value+"_MetierFormation")==-1 && document.getElementById('lesMetiersFormations').value.indexOf(";"+elementsMetier[refmetier].value+"_1_"+elements[c].value+"_MetierFormation")==-1)
								{
									document.getElementById('lesMetiersFormations').value = document.getElementById('lesMetiersFormations').value+";"+elementsMetier[refmetier].value+"_"+document.getElementById('Obligatoire').value+"_"+elements[c].value+"_MetierFormation";
									var table = document.getElementById("tab_MetierFormation");
									var row = table.insertRow();
									row.id = elementsMetier[refmetier].value+"_"+document.getElementById('Obligatoire').value+"_"+elements[c].value+"_MetierFormation";
									btn="";
									if(document.getElementById('DroitFormation').value!=""){
										if(document.getElementById('Langue').value=="FR")
										{
											btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerMetierFormation('"+elementsMetier[refmetier].value+"_"+document.getElementById('Obligatoire').value+"_"+elements[c].value+"')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
										}
										else
										{
											btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerMetierFormation('"+elementsMetier[refmetier].value+"_"+document.getElementById('Obligatoire').value+"_"+elements[c].value+"')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Delete\" title=\"Delete\">&nbsp;&nbsp;</a>";
										}
									}
									var Metier = "";
									var Formation = "";
									var Obligatoire = "";
									for(i=0;i<ListeMetier.length;i++)
									{
										if (ListeMetier[i][0]==elementsMetier[refmetier].value){Metier = ListeMetier[i][1];}
									}
									for(i=0;i<ListeFormation.length;i++)
									{
										if (ListeFormation[i][0]==elements[c].value){Formation = ListeFormation[i][2]+" ("+ListeFormation[i][3]+")";}
									}
									if((document.getElementById('metier').value==0 || elementsMetier[refmetier].value==document.getElementById('metier').value) && (document.getElementById('formation').value==0 || elements[c].value==document.getElementById('formation').value))
									{
										visible="";
									}
									else
									{
										row.style.display="none";
									}
									
									if(document.getElementById('Langue').value=="FR")
									{
										if(document.getElementById('Obligatoire').value==1){Obligatoire="Obligatoire";}else{Obligatoire="Facultative";}
									}
									else
									{
										if(document.getElementById('Obligatoire').value==1){Obligatoire="Mandatory";}else{Obligatoire="Optional";}
									}
									
									var cell = row.insertCell(0);
									cell.innerHTML = Metier;
									cell.style.borderBottom = "1px dotted #000000";
									var cell = row.insertCell(1);
									cell.innerHTML = Formation;
									cell.style.borderBottom = "1px dotted #000000";
									var cell = row.insertCell(2);
									cell.innerHTML = Obligatoire;
									cell.style.borderBottom = "1px dotted #000000";
									var cell = row.insertCell(3);
									cell.innerHTML = btn;
									cell.style.borderBottom = "1px dotted #000000";
									
									document.getElementById('annuler').style.display="";
									document.getElementById('save').style.display="";
									
									SupprimerMetierFormationASuppr(elementsMetier[refmetier].value+"_0_"+elements[c].value);
									SupprimerMetierFormationASuppr(elementsMetier[refmetier].value+"_1_"+elements[c].value);
								}
								else
								{
									for(i=0;i<ListeMetier.length;i++)
									{
										if (ListeMetier[i][0]==elementsMetier[refmetier].value){Metier = ListeMetier[i][1];}
									}
									for(i=0;i<ListeFormation.length;i++)
									{
										if (ListeFormation[i][0]==elements[c].value){Formation = ListeFormation[i][2]+" ("+ListeFormation[i][3]+")";}
									}
									if(Formation!="" && Metier!="")
									{
										if(document.getElementById('Langue').value=="FR"){alert("La formation "+Formation+" existe déjà pour le métier "+Metier);}
										else{alert("Training "+Formation+" already exists for job "+Metier);}
									}
								}
							}
						}
					}
				}
			}
			for(var refmetier=0, nbMetier=elementsMetier.length; refmetier<nbMetier; refmetier++){
				elementsMetier[refmetier].checked=false;
			}
			for(var c=0, l=elements.length; c<l; c++)
			{
				elements[c].checked=false;
			}
		}
	}
	else
	{
		//Cas d'une copie de prestation
		var PrestationCopie=document.getElementById('Id_PrestationCopie').value;
		
		//ListeMetierFormation2 (détails des champs)
		//0 : Id
		//1 : IdPrestation_IdPole
		//2 : Libellé métier
		//3 : Référence formation
		//4 : Obligatoire/Facultatif
		//5 : Id_Metier
		//6 : Obligatoire/Facultatif
		//7 : Id_Formation
		//8 : Libellé formation & Organisme
		

		document.getElementById('laListeMetierFormation2').value="";
		$.ajax({
			url : 'Ajax_ListeMetierFormation.php',
			data : 'Id_PrestationPole='+PrestationCopie,
			dataType: "html",
			async : false,
			//affichage de l'erreur en cas de problème
			error:function(msg, string){
				},
			success:function(data){
					document.getElementById('laListeMetierFormation2').value=data;
				}
		});
		
		ListeMetierFormation2 = new Array();
		leTableau=document.getElementById('laListeMetierFormation2').value.substring(document.getElementById('laListeMetierFormation2').value.indexOf("<tableau>")+9,document.getElementById('laListeMetierFormation2').value.indexOf("</tableau>"));
		var tab = leTableau.split('<tab>');
		val=0
		for (var i=0; i < tab.length; i++){
			if(tab[i]!=""){
				var tabSepare = tab[i].split('<separe>');
				ListeMetierFormation2[val] = new Array(tabSepare[0],tabSepare[1],tabSepare[2],tabSepare[3],tabSepare[4],tabSepare[5],tabSepare[6],tabSepare[7],tabSepare[8],tabSepare[9],tabSepare[10]);
				val++;
			}
		}
		if(document.getElementById('Id_PrestationCopie').value != "" && document.getElementById('Id_PrestationCopie').value != document.getElementById('Id_Prestation').value)
		{
			for(var k=0, l=Elements_CopiePrestaMetierCheck.length; k<l; k++)
			{
				if(Elements_CopiePrestaMetierCheck[k].checked==true)
				{
					for(var i=0, m=ListeMetierFormation2.length; i<m; i++)
					{
						if(Elements_CopiePrestaMetierCheck[k].value==ListeMetierFormation2[i][6])
						{
							if(document.getElementById('lesMetiersFormations').value.indexOf(";"+ListeMetierFormation2[i][6]+"_0_"+ListeMetierFormation2[i][7]+"_MetierFormation")==-1 && document.getElementById('lesMetiersFormations').value.indexOf(";"+ListeMetierFormation2[i][6]+"_1_"+ListeMetierFormation2[i][7]+"_MetierFormation")==-1)
							{
								document.getElementById('lesMetiersFormations').value = document.getElementById('lesMetiersFormations').value+";"+ListeMetierFormation2[i][6]+"_"+ListeMetierFormation2[i][5]+"_"+ListeMetierFormation2[i][7]+"_MetierFormation";
								var table = document.getElementById("tab_MetierFormation");
								var row = table.insertRow();
								row.id = ListeMetierFormation2[i][6]+"_"+ListeMetierFormation2[i][5]+"_"+ListeMetierFormation2[i][7]+"_MetierFormation";
								btn="";
								if(document.getElementById('DroitFormation').value!=""){
									if(document.getElementById('Langue').value=="FR")
									{
										btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerMetierFormation('"+ListeMetierFormation2[i][6]+"_"+ListeMetierFormation2[i][5]+"_"+ListeMetierFormation2[i][7]+"')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
									}
									else
									{
										btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerMetierFormation('"+ListeMetierFormation2[i][6]+"_"+ListeMetierFormation2[i][5]+"_"+ListeMetierFormation2[i][7]+"')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Delete\" title=\"Delete\">&nbsp;&nbsp;</a>";
									}
								}
								var Metier = "";
								var Formation = "";
								var Obligatoire = "";
								for(j=0;j<ListeMetier.length;j++)
								{
									if (ListeMetier[j][0]==ListeMetierFormation2[i][6]){Metier = ListeMetier[j][1];}
								}
								for(j=0;j<ListeFormation.length;j++)
								{
									if (ListeFormation[j][0]==ListeMetierFormation2[i][7]){Formation = ListeFormation[j][2]+" ("+ListeFormation[j][3]+")";}
								}
								if((document.getElementById('metier').value==0 || ListeMetierFormation2[i][6]==document.getElementById('metier').value))
								{
									visible="";
								}
								else
								{
									row.style.display="none";
								}
								
								if(document.getElementById('Langue').value=="FR")
								{
									if(ListeMetierFormation2[i][5]==1){Obligatoire="Obligatoire";}else{Obligatoire="Facultative";}
								}
								else
								{
									if(ListeMetierFormation2[i][5]==1){Obligatoire="Mandatory";}else{Obligatoire="Optional";}
								}
								
								var cell = row.insertCell(0);
								cell.innerHTML = Metier;
								cell.style.borderBottom = "1px dotted #000000";
								var cell = row.insertCell(1);
								cell.innerHTML = Formation;
								cell.style.borderBottom = "1px dotted #000000";
								var cell = row.insertCell(2);
								cell.innerHTML = Obligatoire;
								cell.style.borderBottom = "1px dotted #000000";
								var cell = row.insertCell(3);
								cell.innerHTML = btn;
								cell.style.borderBottom = "1px dotted #000000";
								
								document.getElementById('annuler').style.display="";
								document.getElementById('save').style.display="";
								
								SupprimerMetierFormationASuppr(ListeMetierFormation2[i][6]+"_0_"+ListeMetierFormation2[i][7]);
								SupprimerMetierFormationASuppr(ListeMetierFormation2[i][6]+"_1_"+ListeMetierFormation2[i][7]);
							}
						}
					}
				}
			}
		}
	}
}

function DecocherFormations()
{
	var Choix=0;
	
	//Pour connaitre quel choix a été fait sur les boutons radios
	var Affichage_Choix = document.getElementsByName('Affichage_Choix');
	for (var j=0; j<Affichage_Choix.length; j++)
	{
		if (Affichage_Choix[j].checked)
		{
			Choix=Affichage_Choix[j].value;
			break;
		}
	}
	
	if(Choix==1)
	{
		document.getElementById('Id_MetierCopie').value="";
		var tab = document.getElementsByTagName("input"); 
		for (var i = 0; i < tab.length; i++)
		{ 
			if (tab[i].type == "checkbox")
				tab[i].checked = false;
		}
	}
}

//<![CDATA[
$(window).load(function()
{
(function() {
	var previousPresta;
	var previousMetierFormations;
	var displaySave;
	
	//Plateforme
  $("select[name=Id_Plateforme]").focus(function() {
	// Store the current value on focus, before it changes
	previousPresta=document.getElementById("Id_Prestation").value;
	previousMetierFormations=document.getElementById("lesMetiersFormations").value;
	displaySave=document.getElementById('save').style.display;
	document.getElementById("lIdPresta").value=document.getElementById("Id_Prestation").value;
	document.getElementById("lMetierFormation").value=document.getElementById("lesMetiersFormations").value;
  }).change(function() {
	if (displaySave!="none") {
		if(confirm('Voulez-vous sauvegarder ?')){
			if(previousPresta!=""){
				// Do soomething with the previous value after the change
				var evt = document.createEvent("MouseEvents");
				evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
				document.getElementById("save2").dispatchEvent(evt);
			}
		}
	}
  });
  
  //Prestation
  $("select[name=Id_Prestation]").focus(function() {
	// Store the current value on focus, before it changes
	previousPresta=document.getElementById("Id_Prestation").value;
	previousMetierFormations=document.getElementById("lesMetiersFormations").value;
	displaySave=document.getElementById('save').style.display;
	document.getElementById("lIdPresta").value=document.getElementById("Id_Prestation").value;
	document.getElementById("lMetierFormation").value=document.getElementById("lesMetiersFormations").value;
  }).change(function() {
	if (displaySave!="none") {
		if(document.getElementById('Langue').value=="FR"){question='Voulez-vous sauvegarder ?';}
		else{question='Do you want to save?';}
		if(confirm(question)){
			if(previousPresta!=""){
				// Do soomething with the previous value after the change
				var evt = document.createEvent("MouseEvents");
				evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
				document.getElementById("save2").dispatchEvent(evt);
			}
		}
	}
  });
  
  //Formation
  $("select[name=formation]").focus(function() {
	// Store the current value on focus, before it changes
	previousPresta=document.getElementById("Id_Prestation").value;
	previousMetierFormations=document.getElementById("lesMetiersFormations").value;
	displaySave=document.getElementById('save').style.display;
	document.getElementById("lIdPresta").value=document.getElementById("Id_Prestation").value;
	document.getElementById("lMetierFormation").value=document.getElementById("lesMetiersFormations").value;
  }).change(function() {
	if (displaySave!="none") {
		if(document.getElementById('Langue').value=="FR"){question='Voulez-vous sauvegarder ?';}
		else{question='Do you want to save?';}
		if(confirm(question)){
			if(previousPresta!=""){
				// Do soomething with the previous value after the change
				var evt = document.createEvent("MouseEvents");
				evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
				document.getElementById("save2").dispatchEvent(evt);
			}
		}
	}
  });
  
  //Metier
  $("select[name=metier]").focus(function() {
	// Store the current value on focus, before it changes
	previousPresta=document.getElementById("Id_Prestation").value;
	previousMetierFormations=document.getElementById("lesMetiersFormations").value;
	displaySave=document.getElementById('save').style.display;
	document.getElementById("lIdPresta").value=document.getElementById("Id_Prestation").value;
	document.getElementById("lMetierFormation").value=document.getElementById("lesMetiersFormations").value;
  }).change(function() {
	if (displaySave!="none") {
		if(document.getElementById('Langue').value=="FR"){question='Voulez-vous sauvegarder ?';}
		else{question='Do you want to save?';}
		if(confirm(question)){
			if(previousPresta!=""){
				// Do soomething with the previous value after the change
				var evt = document.createEvent("MouseEvents");
				evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
				document.getElementById("save2").dispatchEvent(evt);
			}
		}
	}
  });
  
  //BOUTON HOME
   //Metier
  $("img[name=boutonHome]").focus(function() {
  }).click(function() {
	if (document.getElementById('save').style.display!="none") {
		if(document.getElementById('Langue').value=="FR"){question='Voulez-vous sauvegarder ?';}
		else{question='Do you want to save?';}
		if(confirm(question)){
			if(document.getElementById("Id_Prestation").value!=""){
				document.getElementById("lIdPresta").value=document.getElementById("Id_Prestation").value;
				document.getElementById("lMetierFormation").value=document.getElementById("lesMetiersFormations").value;
				// Do soomething with the previous value after the change
				var evt = document.createEvent("MouseEvents");
				evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
				document.getElementById("saveHome").dispatchEvent(evt);
			}
		}
		else{
			location.href = "Tableau_De_Bord.php";
		}
	}
	else{
		location.href = "Tableau_De_Bord.php";
	}
  });
})();

});//]]> 

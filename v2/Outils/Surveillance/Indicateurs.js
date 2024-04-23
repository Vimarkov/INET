function CochePrestations(Id_Plateforme){
	table = document.getElementsByTagName('input');
	table2 = document.getElementsByTagName('input');
	tableTr = document.getElementsByTagName('tr');
	for (l=0;l<table.length;l++){
		if (table[l].type == 'checkbox'){
			if(table[l].value == 'Pla_'+Id_Plateforme && table[l].checked == true){
				for(j=0;j<table2.length;j++){
					if (table2[j].type == 'checkbox'){
						if(table2[j].value.substring(0,table2[j].value.indexOf("_")) == Id_Plateforme){
							table2[j].checked = true;
						}
					}
				}
			}
			else if(table[l].value == 'Pla_'+Id_Plateforme && table[l].checked == false){
				document.getElementById('Image_PlusMoins_'+Id_Plateforme).src="../../Images/Plus.gif";
				for (k=0;k<tableTr.length;k++){
					if(tableTr[k].getAttribute("value") == Id_Plateforme){
						tableTr[k].style.display = "none";
					}
				}
				for(j=0;j<table2.length;j++){
					if (table2[j].type == 'checkbox'){
						if(table2[j].value.substring(0,table2[j].value.indexOf("_")) == Id_Plateforme){
							table2[j].checked = false;
						}
					}
				}
			}
		}
	}
}
function AffichePrestations(Id_Plateforme){
	var SourceImage = document.getElementById('Image_PlusMoins_'+Id_Plateforme).src;
	var result = SourceImage.substring(SourceImage.length -6, SourceImage.length);
	table = document.getElementsByTagName('input');tableTr = document.getElementsByTagName('tr');
	if(result == "us.gif"){
		document.getElementById('Image_PlusMoins_'+Id_Plateforme).src="../../Images/Moins.gif";
		for (l=0;l<table.length;l++){
			if (table[l].type == 'checkbox'){
				for (k=0;k<tableTr.length;k++){
					if(tableTr[k].getAttribute("value") == Id_Plateforme){
						tableTr[k].style.display = "";
					}
				}
			}
		}
	}
	else{
		document.getElementById('Image_PlusMoins_'+Id_Plateforme).src="../../Images/Plus.gif";
		for (l=0;l<table.length;l++){
			if (table[l].type == 'checkbox'){
				for (k=0;k<tableTr.length;k++){
					if(tableTr[k].getAttribute("value") == Id_Plateforme){
						tableTr[k].style.display = "none";
					}
				}
			}
		}
	}
}
function AfficheTypeLegende(){
	tableTr = document.getElementsByTagName('tr');
	table = document.getElementsByTagName('input');
	if(document.getElementById("legende").checked == true){
		for (k=0;k<tableTr.length;k++){
			if(tableTr[k].getAttribute("value") == 'nbSurveillance'){
				tableTr[k].style.display = "";
			}
		}
	}	
	else if(document.getElementById("legende").checked == false){
		for (k=0;k<tableTr.length;k++){
			if(tableTr[k].getAttribute("value") == 'nbSurveillance'){
				tableTr[k].style.display = "none";
			}
		}
	}
	if(document.getElementById("NumNC").checked == true || document.getElementById("NumNA").checked == true){
		document.getElementById("tablePar").style.display = "none";
	}
	else{
		document.getElementById("tablePar").style.display = "";
	}
}
function AfficheGeneSpec(type){
	tableTr = document.getElementsByTagName('tr');
	table = document.getElementsByTagName('input');
	if(type=="Generique"){
		if(document.getElementById("Generique").checked == true){
			for (k=0;k<tableTr.length;k++){
				if(tableTr[k].getAttribute("value") == 'Gene'){
					tableTr[k].style.display = "";
				}
			}
			for(j=0;j<table.length;j++){
				if (table[j].type == 'checkbox'){
					if(table[j].value.substring(0,5) == "Gene_"){
						table[j].checked = true;
					}
				}
			}
		}
		else if(document.getElementById("Generique").checked == false){
			for (k=0;k<tableTr.length;k++){
				if(tableTr[k].getAttribute("value") == 'Gene'){
					//tableTr[k].style.display = "none";
				}
			}
			for(j=0;j<table.length;j++){
				if (table[j].type == 'checkbox'){
					if(table[j].value.substring(0,5) == "Gene_"){
						table[j].checked = false;
					}
				}
			}
		}
	}
	if(type=="Specifique"){
		if(document.getElementById("Specifique").checked == true){
			for (k=0;k<tableTr.length;k++){
				if(tableTr[k].getAttribute("value") == 'Spec'){
					tableTr[k].style.display = "";
				}
			}
			for(j=0;j<table.length;j++){
				if (table[j].type == 'checkbox'){
					if(table[j].value.substring(0,5) == "Spec_"){
						table[j].checked = true;
					}
				}
			}
		}
		else if(document.getElementById("Specifique").checked == false){
			for (k=0;k<tableTr.length;k++){
				if(tableTr[k].getAttribute("value") == 'Spec'){
					//tableTr[k].style.display = "none";
				}
			}
			for(j=0;j<table.length;j++){
				if (table[j].type == 'checkbox'){
					if(table[j].value.substring(0,5) == "Spec_"){
						table[j].checked = false;
					}
				}
			}
		}
	}
}
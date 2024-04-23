function Fermer()
{
	window.close();
}

Liste_Niveau = new Array();
Liste_Pole_Prestation = new Array();
Liste_Frequence = new Array();
bvisible = false;
function Recharge_Liste_Pole(){
	var i;
	var sel="";
	var isElement = false;

	console.log("Recharge_Liste_Pole()");
	sel ="<select id='poles' size='1' name='pole' onchange='Rechercher_Frequence();'>\n";
	for(i=0;i<Liste_Pole_Prestation.length;i++){
		if (Liste_Pole_Prestation[i][1]==document.getElementById('prestation').value){
			sel= sel + "<option value="+Liste_Pole_Prestation[i][0]+">"+Liste_Pole_Prestation[i][2]+"</option>\n";
			isElement = true;
		}
	}
	
	if(isElement == false){sel= sel + "<option name='0' value='0' selected></option>\n";}
	sel =sel + "</select>\n";
	console.log(sel);
	document.getElementById('pole').innerHTML=sel;

	Rechercher_Frequence();
}

function Rechercher_Frequence(){
	var i;
	document.getElementById('IdFrequence').value="";
	document.getElementById('frequence').value=0;
	document.getElementById('jour1s').value=0;
	document.getElementById('jour2s').value=0;
	document.getElementById('heure1s').value=0;
	document.getElementById('heure2s').value=0;
	
	document.getElementById('rougeS').value="";
	document.getElementById('rougeQ').value="";
	document.getElementById('rougeC').value="";
	document.getElementById('rougeD').value="";
	document.getElementById('rougeP').value="";
	document.getElementById('rougeF').value="";
	document.getElementById('vertS').value="";
	document.getElementById('vertQ').value="";
	document.getElementById('vertC').value="";
	document.getElementById('vertD').value="";
	document.getElementById('vertP').value="";
	document.getElementById('vertF').value="";
	
	for(i=0; i<Liste_Frequence.length; i++){
		Freq = Liste_Frequence[i];
		
		if (Freq[1]==document.getElementById('prestation').value && Freq[2]==document.getElementById('poles').value){
			console.log("Rechercher_Frequence() - Dans le if !!!");
			document.getElementById('IdFrequence').value=Freq[0];
			document.getElementById('frequence').value=Freq[3];
			document.getElementById('jour1s').value=Freq[4];
			document.getElementById('jour2s').value=Freq[5];
			document.getElementById('heure1s').value=Freq[6];
			document.getElementById('heure2s').value=Freq[7];
			
			document.getElementById('rougeS').value=Freq[8];
			document.getElementById('rougeQ').value=Freq[9];
			document.getElementById('rougeC').value=Freq[10];
			document.getElementById('rougeD').value=Freq[11];
			document.getElementById('rougeP').value=Freq[12];
			document.getElementById('rougeF').value=Freq[13];
			document.getElementById('vertS').value=Freq[14];
			document.getElementById('vertQ').value=Freq[15];
			document.getElementById('vertC').value=Freq[16];
			document.getElementById('vertD').value=Freq[17];
			document.getElementById('vertP').value=Freq[18];
			document.getElementById('vertF').value=Freq[19];
		}
	}
	
	AfficherChamps();
}

function AfficherChamps(){
	if(document.getElementById('frequence').value == 3){
		document.getElementById('1er1').style.display="";
		document.getElementById('1er2').style.display="";
		document.getElementById('2eme').style.display="";
	}
	else if(document.getElementById('frequence').value == 2){
		document.getElementById('1er1').style.display="";
		document.getElementById('1er2').style.display="";
		document.getElementById('2eme').style.display="none";
	}
	else{
		document.getElementById('1er1').style.display="none";
		document.getElementById('1er2').style.display="none";
		document.getElementById('2eme').style.display="none";
	}
}

function VerifChamps(){
	if(document.getElementById('prestation').value=='0'){
		alert('Vous n\'avez pas renseign� la prestation.');
		return false;
	}
	if(document.getElementById('frequence').value=='0'){
		alert('Vous n\'avez pas renseign� la fr�quence.');
		return false;
	}
	if(document.getElementById('heure1s').value=='0'){
		alert('Vous n\'avez pas renseign� l\'heure de r�alisation.');
		return false;
	}
	if(document.getElementById('frequence').value=='3'){
		if(document.getElementById('jour1s').value=='0'){
			alert('Vous n\'avez pas renseign� le jour de r�alisation.');
			return false;
		}
		if(document.getElementById('heure2s').value=='0'){
			alert('Vous n\'avez pas renseign� la 2�me heure de r�alisation.');
			return false;
		}
		if(document.getElementById('jour2s').value=='0'){
			alert('Vous n\'avez pas renseign� le 2�me jour de r�alisation.');
			return false;
		}
	}
	else if(document.getElementById('frequence').value=='2'){
		if(document.getElementById('jour1s').value=='0'){
			alert('Vous n\'avez pas renseign� le jour de r�alisation.');
			return false;
		}
	}
	return true;
}
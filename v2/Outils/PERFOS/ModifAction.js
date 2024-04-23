function FermerEtRecharger(Id_Prestation,Id_Pole,dateEnvoi,visionSelect,IdCreateurSelect,IdActeurSelect,AvancementSelect,NiveauSelect,LettreSelect)
{
	opener.location.href="Liste_Action.php?IdPrestationSelect="+Id_Prestation+"&IdPoleSelect="+Id_Pole+"&DateSelect="+dateEnvoi+"&VisionSelect="+visionSelect+"&IdCreateurSelect="+IdCreateurSelect+"&IdActeurSelect="+IdActeurSelect+"&AvancementSelect="+AvancementSelect+"&NiveauSelect="+NiveauSelect+"&LettreSelect="+LettreSelect;
	window.close();
}

Liste_Niveau = new Array();
Liste_Pole_Prestation = new Array();
minPoste = 0;
maxPoste = 0;
bvisible = false;
function Recharge_Liste_Pole(){
	var i;
	var sel="";
	var isElement = false;
	sel =" <select id='poles' size='1' name='pole' onchange='Rechercher_Action();'>";
	for(i=0;i<Liste_Pole_Prestation.length;i++){
		if (Liste_Pole_Prestation[i][1]==document.getElementById('prestation').value){
			sel= sel + "<option value="+Liste_Pole_Prestation[i][0]+">"+Liste_Pole_Prestation[i][2]+"</option>";
			isElement = true;
		}
	}
	if(isElement == false){sel= sel + "<option value='0' selected></option>";}
	sel =sel + "</select>";
	document.getElementById('pole').innerHTML=sel;

	Rechercher_Action();
}

function Rechercher_Action(){
	var i;
	var sel="";
	var isElement = false;
	sel =" <select id='niveaux' name='niveaux' size='1' onchange='AfficherChamps(0);'>";
	minPoste = 0;
	maxPoste = 0;
	for(i=0;i<Liste_Niveau.length;i++){
		if (Liste_Niveau[i][0]==document.getElementById('prestation').value && Liste_Niveau[i][1]==document.getElementById('poles').value){
			if(minPoste == 0){
				minPoste = Liste_Niveau[i][2];
				maxPoste = Liste_Niveau[i][2];
			}
			else{
				if (Liste_Niveau[i][2] < minPoste){minPoste =Liste_Niveau[i][2];}
				if (Liste_Niveau[i][2] > maxPoste){maxPoste =Liste_Niveau[i][2];}
			}
		}
	}
	if(document.getElementById('prestation').value == 0){
		sel= sel + "<option value='0'></option>";
	}
	else{
		if(minPoste < 3){
			sel= sel + "<option value='1'>1</option>";
			sel= sel + "<option value='2'>2</option>";
			if(maxPoste == 3 || maxPoste == 5){
				sel= sel + "<option value='3'>3</option>";
			}
			if(maxPoste == 4 || maxPoste == 6 || maxPoste == 7 || maxPoste == 8 || maxPoste == 9){
				sel= sel + "<option value='3'>3</option>";
				sel= sel + "<option value='4'>4</option>";
			}
		}
		else if(minPoste == 3 || minPoste == 5){
			sel= sel + "<option value='1'>1</option>";
			sel= sel + "<option value='2'>2</option>";
			sel= sel + "<option value='3'>3</option>";
			if(maxPoste == 4 || maxPoste == 6 || maxPoste == 7 || maxPoste == 8 || maxPoste == 9){
				sel= sel + "<option value='4'>4</option>";
			}
		}
		else if(minPoste == 4 || minPoste == 6 || minPoste == 7){
			sel= sel + "<option value='2'>2</option>";
			sel= sel + "<option value='3'>3</option>";
			sel= sel + "<option value='4'>4</option>";
		}
		else if(minPoste == 8 || minPoste == 9){
			sel= sel + "<option value='3'>3</option>";
			sel= sel + "<option value='4'>4</option>";
		}
	}
	sel =sel + "</select>";
	document.getElementById('niveau').innerHTML=sel;
	AfficherChamps(0);
}

function AfficherChamps(option){
	if(option==0){
		if(document.getElementById('prestation').value == 0){
			document.getElementById('ligneAvancement1').style.display="none";
			document.getElementById('ligneAvancement2').style.display="none";
			document.getElementById('btnValider').style.display="none";
			
			document.getElementById('ligneN0').style.display="none";
			document.getElementById('ligneN1').style.display="none";
			document.getElementById('ligneN2').style.display="none";
			document.getElementById('ligneN3').style.display="none";
			document.getElementById('ligneN4').style.display="none";
			document.getElementById('ligneN5').style.display="none";
			bvisible = false;
		}
		else{
			document.getElementById('btnValider').style.display="";
			if(document.getElementById('niveaux').value == 1){
				if (minPoste < 3){bvisible = true;}
				else{bvisible = false;}
			}
			else if(document.getElementById('niveaux').value == 2){
				if (minPoste ==3 || minPoste ==5 || (minPoste<3 && maxPoste>2)){bvisible = true;}
				else{bvisible = false;}
			}
			else if(document.getElementById('niveaux').value == 3){
				if (minPoste ==4 || minPoste ==6 || minPoste ==7 || ((minPoste==1 || minPoste==2  || minPoste==3  || minPoste==5) && maxPoste>3)){bvisible = true;}
				else{bvisible = false;}
			}
			else if(document.getElementById('niveaux').value == 4){
				if (minPoste ==8 || minPoste ==9  || (minPoste<8 && maxPoste>7)){bvisible = true;}
				else{bvisible = false;}
			}
			if(bvisible == true){
				document.getElementById('ligneAvancement1').style.display="";
				document.getElementById('ligneAvancement2').style.display="";
				if(document.getElementById('niveaux').value == 1){
					document.getElementById('Avancement6').style.display="none";
				}
				else if(document.getElementById('niveaux').value == 4){
					document.getElementById('Avancement5').style.display="none";	
				}
				document.getElementById('ligneN0').style.display="none";
				document.getElementById('ligneN1').style.display="none";
				document.getElementById('ligneN2').style.display="none";
				document.getElementById('ligneN3').style.display="none";
				document.getElementById('ligneN4').style.display="none";
				document.getElementById('ligneN5').style.display="none";
				if(document.getElementById('avancement').value >= 4){
					document.getElementById('ligneN0').style.display="";
					document.getElementById('ligneN1').style.display="";
					document.getElementById('ligneN2').style.display="";
					document.getElementById('ligneN3').style.display="";
					document.getElementById('ligneN4').style.display="";
					document.getElementById('ligneN5').style.display="";
				}
			}
			else{
				document.getElementById('ligneAvancement1').style.display="none";
				document.getElementById('ligneAvancement2').style.display="none";
				
				document.getElementById('ligneN0').style.display="none";
				document.getElementById('ligneN1').style.display="none";
				document.getElementById('ligneN2').style.display="none";
				document.getElementById('ligneN3').style.display="none";
				document.getElementById('ligneN4').style.display="none";
				document.getElementById('ligneN5').style.display="none";
			}
		}
	}
	else{
		if(document.getElementById('niveaux').value == 1){
			if (minPoste < 3){bvisible = true;}
			else{bvisible = false;}
		}
		else if(document.getElementById('niveaux').value == 2){
			if (minPoste ==3 || minPoste ==5 || (minPoste<3 && maxPoste>2)){bvisible = true;}
			else{bvisible = false;}
		}
		else if(document.getElementById('niveaux').value == 3){
			if (minPoste ==4 || minPoste ==6 || minPoste ==7 || ((minPoste==1 || minPoste==2  || minPoste==3  || minPoste==5) && maxPoste>3)){bvisible = true;}
			else{bvisible = false;}
		}
		else if(document.getElementById('niveaux').value == 4){
		
			if (minPoste ==8 || minPoste ==9  || (minPoste<8 && maxPoste>7)){bvisible = true;}
			else{bvisible = false;}
		}
		if(bvisible == true){
			document.getElementById('ligneAvancement1').style.display="";
			document.getElementById('ligneAvancement2').style.display="";
			
			document.getElementById('ligneN0').style.display="none";
			document.getElementById('ligneN1').style.display="none";
			document.getElementById('ligneN2').style.display="none";
			document.getElementById('ligneN3').style.display="none";
			document.getElementById('ligneN4').style.display="none";
			document.getElementById('ligneN5').style.display="none";
			if(document.getElementById('avancement').value >= 5){
				document.getElementById('ligneN0').style.display="";
				document.getElementById('ligneN1').style.display="";
				document.getElementById('ligneN2').style.display="";
				document.getElementById('ligneN3').style.display="";
				document.getElementById('ligneN4').style.display="";
				document.getElementById('ligneN5').style.display="";
			}
		}
		else{
			document.getElementById('ligneAvancement1').style.display="none";
			document.getElementById('ligneAvancement2').style.display="none";

				document.getElementById('ligneN0').style.display="none";
				document.getElementById('ligneN1').style.display="none";
				document.getElementById('ligneN2').style.display="none";
				document.getElementById('ligneN3').style.display="none";
				document.getElementById('ligneN4').style.display="none";
				document.getElementById('ligneN5').style.display="none";			
		}
	}
}
function AfficherAvancement(){
	var sel="";
	sel ="";
	if(document.getElementById('avancement').value == 0){
		sel ="<img src='../../Images/NonPrisEnCompte.gif' border='0' alt='NonPrisEnCompte' title='Non pris en compte'>";
		document.getElementById('titreCloture').style.display="none";
		document.getElementById('corpsCloture').style.display="none";
		document.getElementById('ligneN0').style.display="none";
		document.getElementById('ligneN1').style.display="none";
		document.getElementById('ligneN2').style.display="none";
		document.getElementById('ligneN3').style.display="none";
		document.getElementById('ligneN4').style.display="none";
		document.getElementById('ligneN5').style.display="none";
	}
	else if(document.getElementById('avancement').value == 1){
		sel ="<img src='../../Images/EnCompte.gif' border='0' alt='EnCompte' title='En compte'>";
		document.getElementById('titreCloture').style.display="none";
		document.getElementById('corpsCloture').style.display="none";
		document.getElementById('ligneN0').style.display="none";
		document.getElementById('ligneN1').style.display="none";
		document.getElementById('ligneN2').style.display="none";
		document.getElementById('ligneN3').style.display="none";
		document.getElementById('ligneN4').style.display="none";
		document.getElementById('ligneN5').style.display="none";
	}
	else if(document.getElementById('avancement').value == 2){
		sel ="<img src='../../Images/EnCours.gif' border='0' alt='EnCours' title='En cours'>";
		document.getElementById('titreCloture').style.display="none";
		document.getElementById('corpsCloture').style.display="none";
		document.getElementById('ligneN0').style.display="none";
		document.getElementById('ligneN1').style.display="none";
		document.getElementById('ligneN2').style.display="none";
		document.getElementById('ligneN3').style.display="none";
		document.getElementById('ligneN4').style.display="none";
		document.getElementById('ligneN5').style.display="none";
	}
	else if(document.getElementById('avancement').value == 3){
		sel ="<img src='../../Images/Solution.gif' border='0' alt='Solution' title='Solution/action'>";
		document.getElementById('titreCloture').style.display="none";
		document.getElementById('corpsCloture').style.display="none";
		document.getElementById('ligneN0').style.display="none";
		document.getElementById('ligneN1').style.display="none";
		document.getElementById('ligneN2').style.display="none";
		document.getElementById('ligneN3').style.display="none";
		document.getElementById('ligneN4').style.display="none";
		document.getElementById('ligneN5').style.display="none";
	}
	else if(document.getElementById('avancement').value == 4){
		sel ="<img src='../../Images/Cloturee.gif' border='0' alt='Cloturee' title='Clotur�e'>";
		document.getElementById('titreCloture').style.display="";
		document.getElementById('corpsCloture').style.display="";
		document.getElementById('ligneN0').style.display="none";
		document.getElementById('ligneN1').style.display="none";
		document.getElementById('ligneN2').style.display="none";
		document.getElementById('ligneN3').style.display="none";
		document.getElementById('ligneN4').style.display="none";
		document.getElementById('ligneN5').style.display="none";
	}
	else if(document.getElementById('avancement').value > 4){
		sel ="<img src='../../Images/Cloturee.gif' border='0' alt='Cloturee' title='Clotur�e'>";
		document.getElementById('titreCloture').style.display="";
		document.getElementById('corpsCloture').style.display="";
		document.getElementById('ligneN0').style.display="";
		document.getElementById('ligneN1').style.display="";
		document.getElementById('ligneN2').style.display="";
		document.getElementById('ligneN3').style.display="";
		document.getElementById('ligneN4').style.display="";
		document.getElementById('ligneN5').style.display="";
	}
	document.getElementById('ImgAvancement').innerHTML=sel;
}

function VerifChamps(){
	if(document.getElementById('Id_Action').value=='0'){
		if(document.getElementById('prestation').value=='0'){
			alert('Vous n\'avez pas renseign� la prestation.');
			return false;
		}
		if(document.getElementById('probleme').value==''){
			alert('Vous n\'avez pas renseign� le probl�me.');
			return false;
		}
		if(bvisible == true){
			document.getElementById('bvisible').value = 1;
			if(document.getElementById('action').value==''){
				alert('Vous n\'avez pas renseign� l\'action.');
				return false;
			}
			if(document.getElementById('delais').value==''){
				alert('Vous n\'avez pas renseign� le d�lais.');
				return false;
			}
			if(document.getElementById('avancement').value>=4){
				if(document.getElementById('dateCloture').value==''){
					alert('Vous n\'avez pas renseign� la date de cl�ture.');
					return false;
				}
			}
		}
		else{
			document.getElementById('bvisible').value = 0;
		}
	}
	else{
		if(document.getElementById('probleme').value==''){
			alert('Vous n\'avez pas renseign� le probl�me.');
			return false;
		}
		if(bvisible == true){
			document.getElementById('bvisible').value = 1;
			if(document.getElementById('action').value==''){
				alert('Vous n\'avez pas renseign� l\'action.');
				return false;
			}
			if(document.getElementById('delais').value==''){
				alert('Vous n\'avez pas renseign� le d�lais.');
				return false;
			}
			if(document.getElementById('avancement').value>=4){
				if(document.getElementById('dateCloture').value==''){
					alert('Vous n\'avez pas renseign� la date de cl�ture.');
					return false;
				}
			}
		}
		else{
			document.getElementById('bvisible').value = 0;
		}
	}
	return true;
}
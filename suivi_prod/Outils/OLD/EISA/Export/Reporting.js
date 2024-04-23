function OuvreFenetreAjout_Critere(){
	var w=window.open("Ajout_Critere.php?Type=A","PageCritere","status=no,menubar=no,scrollbars=yes,width=500,height=250");
	w.focus();
}
function OuvreFenetreAjout_CritereECME(){
	var w=window.open("Ajout_CritereECME.php?Type=A","PageCritere","status=no,menubar=no,scrollbars=yes,width=500,height=250");
	w.focus();
}
function Suppr_CritereECME(critere,valeur){
	var w=window.open("Ajout_CritereECME.php?Type=S&critere="+critere+"&valeur="+valeur,"PageCritere","status=no,menubar=no,scrollbars=yes,width=600,height=400");
	w.focus();
}
function OuvreFenetreAjout_CritereING(){
	var w=window.open("Ajout_CritereING.php?Type=A","PageCritere","status=no,menubar=no,scrollbars=yes,width=500,height=250");
	w.focus();
}
function Suppr_CritereING(critere,valeur){
	var w=window.open("Ajout_CritereING.php?Type=S&critere="+critere+"&valeur="+valeur,"PageCritere","status=no,menubar=no,scrollbars=yes,width=600,height=400");
	w.focus();
}
function OuvreFenetreAjout_CriterePS(){
	var w=window.open("Ajout_CriterePS.php?Type=A","PageCritere","status=no,menubar=no,scrollbars=yes,width=500,height=250");
	w.focus();
}
function Suppr_CriterePS(critere,valeur){
	var w=window.open("Ajout_CriterePS.php?Type=S&critere="+critere+"&valeur="+valeur,"PageCritere","status=no,menubar=no,scrollbars=yes,width=600,height=400");
	w.focus();
}
function OuvreFenetreAjout_CritereECMECLIENT(){
	var w=window.open("Ajout_CritereECMECLIENT.php?Type=A","PageCritere","status=no,menubar=no,scrollbars=yes,width=500,height=250");
	w.focus();
}
function Suppr_CritereECMECLIENT(critere,valeur){
	var w=window.open("Ajout_CritereECMECLIENT.php?Type=S&critere="+critere+"&valeur="+valeur,"PageCritere","status=no,menubar=no,scrollbars=yes,width=600,height=400");
	w.focus();
}
function Excel_OQD(){
	var w=window.open("Extract_OQD.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
	w.focus();
}
function Excel_PS(){
	var w=window.open("Extract_PS.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
	w.focus();
}
function Excel_ECME(){
	var w=window.open("Extract_ECME.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
	w.focus();
}
function Excel_ING(){
	var w=window.open("Extract_ING.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
	w.focus();
}
function Excel_PSUtilise(){
	var w=window.open("Extract_PSUtilise.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
	w.focus();
}
function Excel_ECMECLIENT(){
	var w=window.open("Extract_ECMECLIENT.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
	w.focus();
}
function ReportingEmail(date){
	if (date!=""){
		var w=window.open("ReportingEmail.php?date="+date,"PageReporting","status=no,menubar=no,scrollbars=yes,width=60,height=40");
		w.focus();
	}
	else{
		alert("Veuillez renseigner la date");
	}
}
function ExtractMesureECME(ecme,du,au){
	if (ecme!="" && du>"0001-01-01"){
		var w=window.open("Extract_MesureECME.php?ecme="+ecme+"&du="+du+"&au="+au,"PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
		w.focus();
	}
	else{
		alert("Veuillez compléter les critères");
	}
}
function Excel_KPI(){
	if(document.getElementById('duKPI').value!="" && document.getElementById('auKPI').value!=""){
		var w=window.open("Extract_KPI.php?du="+document.getElementById('duKPI').value+"&au="+document.getElementById('auKPI').value,"PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
		w.focus();
	}
}
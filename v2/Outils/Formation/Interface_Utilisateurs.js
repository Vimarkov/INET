function FermerEtRecharger()
{
	if(window.opener.document.getElementById('formulaire')){
	window.opener.document.getElementById('formulaire').submit();
	}
	window.close();
}
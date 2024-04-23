$(function(){
	$(window).scroll(
		function () {//Au scroll dans la fenetre on d?clenche la fonction
			if ($(this).scrollTop() > 1) { //si on a d?fil? de plus de 150px du haut vers le bas
				$('#navigation').addClass("fixNavigation"); //on ajoute la classe "fixNavigation" ? <div id="navigation">
			} else {
				$('#navigation').removeClass("fixNavigation");//sinon on retire la classe "fixNavigation" ? <div id="navigation">
			}
		}
	);			 
});
/*
*funcion que permite animar elementos usando la libreria animate.css
*elemento  => ID del elemento a animar
*animacion => Animacion para aplicar al elemento
*infinita  => si la animacion es infinita o no 
*https://github.com/daneden/animate.css
*/
function animar_elemento(elemento,animacion,infinita){
	infinita = typeof infinita !== 'undefined' ? infinita : false;
	$('#'+elemento).addClass('animated').addClass(animacion);
	if (infinita) {
		$('#'+elemento).addClass('infinite');	
	}else{
		setTimeout(function(){
			$('#'+elemento).removeClass('animated').removeClass(animacion);
		},1000);
	};
	
};


function confirm(e,l){
	alert($(l).attr('href'));
	e.preventDefault();
	return!1;
}
(function($){
	window.on('devicelight', function(e){
		var lux = e.value;
		$("#lux-level").text(lux);
		/*if (lux < 50) { // luz tenue
	    	document.body.className = 'tenue';
		}
		if (lux >= 50 && lux <= 1500) { //luz normal
	    	document.body.className = 'normal';
		}
		if (lux > 1500)  { // mucha luz
	    	document.body.className = 'luminoso';
		}*/
	});
})(jQuery);
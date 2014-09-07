(function($){
	window.addEventListener('devicelight', function(e){
		var lux = e.value;
		$("#lux-level").text(lux);
		if (lux < 50) { // luz tenue
	    	$('body').css({
	    		'background':'black',
	    		'color': 'white'
	    	});
		}
		if (lux >= 50 && lux <= 1500) { //luz normal
	    	$('body').css({
	    		'background':'gray',
	    		'color': 'blue'
	    	});
		}
		if (lux > 1500)  { // mucha luz
	    	$('body').css({
	    		'background':'white',
	    		'color': 'black'
	    	});
		}
	});
})(jQuery);
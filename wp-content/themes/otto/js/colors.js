(function($) {
	function handleEvent(event) {

		// accelerometer 
		var z = event.alpha + 180;          // 0, 360
		var x = event.beta + 180;           // 0, 360
		var y = (event.gamma + 90) * 2;     // 0, 180

		$('#data-x').text( Math.floor(x) );
		$('#data-y').text( Math.floor(y) );
		$('#data-z').text( Math.floor(z) );

		window.console && console.info('Raw Position: x, y, z: ', x, y, z);
	}

	window.addEventListener("deviceorientation", handleEvent, true);	
})(jQuery);
(function($) {
	function handleEvent(event) {

		// accelerometer 
		var z = event.alpha + 180;          // 0, 360
		var x = event.beta + 180;           // 0, 360
		var y = (event.gamma + 90) * 2;     // 0, 360

		$('#data-x').text( Math.floor(x) );
		$('#data-y').text( Math.floor(y) );
		$('#data-z').text( Math.floor(z) );

		// RGB
		var r = changeRangeTo255( x ); // 0, 255
		var g = changeRangeTo255( y ); // 0, 255
		var b = changeRangeTo255( z ); // 0, 255

		$('#data-r').text( Math.floor(r) );
		$('#data-g').text( Math.floor(g) );
		$('#data-b').text( Math.floor(b) );

		// HSL
		var h = x; // 0, 360
		var s = changeRangeTo100( y ); // 0, 100
		var l = changeRangeTo100( z ); // 0, 100

		$('#data-h').text( Math.floor(h) );
		$('#data-s').text( Math.floor(s) );
		$('#data-l').text( Math.floor(l) );

		window.console && console.info('Raw Position: x, y, z: ', x, y, z);
	}

	function changeRangeTo255( val ){
		var converted = ( val * 255 ) / 360;
		return converted;
	}

	function changeRangeTo100( val ){
		var converted = ( val * 100 ) / 360;
		return converted;
	}

	window.addEventListener("deviceorientation", handleEvent, true);	
})(jQuery);
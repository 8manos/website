(function($) {
	function handleEvent(event) {

		// accelerometer 

		// raw
		var r_x = event.beta;           // 0, 360
		var r_y = event.gamma; 
		var r_z = event.alpha;                // 0, 360 // sim: + 180

		$('#data-r_x').text( Math.floor(r_x) );
		$('#data-r_y').text( Math.floor(r_y) );
		$('#data-r_z').text( Math.floor(r_z) );

		// converted range
		var x = event.beta + 180;           // 0, 360
		var y = (event.gamma + 90) * 2;     // 0, 360
		var z = event.alpha;                // 0, 360 // sim: + 180

		$('#data-x').text( Math.floor(x) );
		$('#data-y').text( Math.floor(y) );
		$('#data-z').text( Math.floor(z) );

		// RGB
		var r = cicloCompleto( x, changeRangeTo255 ); // 0, 255
		var g = cicloCompleto( y, changeRangeTo255 ); // 0, 255
		var b = cicloCompleto( z, changeRangeTo255 ); // 0, 255

		$('#data-r').text( r );
		$('#data-g').text( g );
		$('#data-b').text( b );

		// HSL
		var h = cicloCompleto( x, Math.floor ); // 0, 360
		var s = cicloCompleto( y, changeRangeTo100 ); // 0, 100
		var l = cicloCompleto( z, changeRangeTo100 ); // 0, 100

		$('#data-h').text( h );
		$('#data-s').text( s );
		$('#data-l').text( l );

		// Pruebas de setting de color
		$( 'header' ).css( 'background-color', 'rgb('+r+','+g+','+b+')' );
		$( 'body' ).css( 'background-color', 'hsl('+h+','+s+'%,'+l+'%)' );

		window.console && console.info('Raw Position: x, y, z: ', x, y, z);
	}

	function cicloCompleto( val, callback ){
		var converted = val;

		if( val > 180 ){
			converted = 360 - val;
		}
		return callback( converted );

	}

	function changeRangeTo255( val ){
		var converted = ( val * 255 ) / 180;
		return Math.floor( converted );
	}

	function changeRangeTo100( val ){
		var converted = ( val * 100 ) / 180;
		return Math.floor( converted );
	}

	window.addEventListener( 'deviceorientation' , handleEvent, true );	
})(jQuery);
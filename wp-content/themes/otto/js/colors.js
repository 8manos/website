(function($) {

	// raw input for color variations 
	var r_x = event.beta;           
	var r_y = event.gamma; 
	var r_z = event.alpha;                

	function handleEvent(event) {

		// accelerometer 

		$('#data-r_x').text( Math.floor(r_x) );
		$('#data-r_y').text( Math.floor(r_y) );
		$('#data-r_z').text( Math.floor(r_z) );

		// converted range
		var x = r_x + 180;           
		var y = (r_y + 90) * 2;     
		var z = r_z;                

		$('#data-x').text( Math.floor(x) );
		$('#data-y').text( Math.floor(y) );
		$('#data-z').text( Math.floor(z) );

		// RGB
		var r = cicloCompleto( x, changeRangeTo255 ); 
		var g = cicloCompleto( y, changeRangeTo255 ); 
		var b = cicloCompleto( z, changeRangeTo255 ); 

		$('#data-r').text( r );
		$('#data-g').text( g );
		$('#data-b').text( b );

		// HSL
		var h = cicloCompleto( x, Math.floor ); 
		var s = cicloCompleto( y, changeRangeTo100 ); 
		var l = cicloCompleto( z, changeRangeTo100 ); 

		$('#data-h').text( h );
		$('#data-s').text( s );
		$('#data-l').text( l );

		// Setting de color
		$( 'a, .color' ).css( 'color', 'rgb('+r+','+g+','+b+')' );
		$( '.color-bg' ).css( 'background-color', 'rgb('+r+','+g+','+b+')' );

		// HSL Descartado
		// $( 'body' ).css( 'background-color', 'hsl('+h+','+s+'%,'+l+'%)' );

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

	$(window).on( 'deviceorientation' , handleEvent(e) );
		
})(jQuery);
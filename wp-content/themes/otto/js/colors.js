(function($) {

	var r_x = null;           
	var r_y = null; 
	var r_z = null;

	// Para comparar si se mueve en unos segundos
	var r_x_inicial = null;           
	var r_y_inicial = null;
	var r_x_actual = null;
	var r_y_actual = null;

	// Para timeout
	var moved = false;

	if ( window.DeviceOrientationEvent ) {
		window.addEventListener('deviceorientation', handleEvent, true);
	}

	setTimeout( function(){ 
		/*console.log( "timeout" );
		console.log( r_x_inicial );
		console.log( r_y_inicial );*/

		var touch_support = Modernizr.touch;

		if( touch_support == false && ( ( r_x_inicial === r_x_actual && r_y_inicial === r_y_actual ) || ( r_x_inicial === null && r_y_inicial === null ) ) ){
			console.log( 'No me he movido y no tengo touch' );

			r_z = 0;

			$( window ).mousemove(function( event ) {

				r_x = event.pageX;
				r_y = event.pageY;

				handleCursor( r_x, r_y, r_z );

			});

			$( window ).on('mousewheel', function(event) {
				//console.log( event.deltaY );
				r_z = r_z + ( event.deltaY * 2 );
				if( r_z < 0 ){
					r_z = 255;
				}else if( r_z > 255 ){
					r_z = 0;
				}

				handleCursor( r_x, r_y, r_z );

			});

			function handleCursor( r_x, r_y, r_z ){

				var doc_height = $(window).height();
				var doc_width = $(window).width();

				var x_percentage = percentageOfTotal( r_x, doc_width );
				var y_percentage = percentageOfTotal( r_y, doc_height );

				var r = changeRange100To255( x_percentage );
				var g = changeRange100To255( y_percentage );
				var b = r_z;

				// Setting de color
				setRGB( r, g, b );

			}

		}else if( Modernizr.touch && ( ( r_x_inicial === r_x_actual && r_y_inicial === r_y_actual ) || ( r_x_inicial === null && r_y_inicial === null ) ) ){
			console.log( 'Touch pero quieto' );

			randomColor();
			setInterval( randomColor() , 5000 );

		}else{
			console.log( 'Me moví ');
		}

	}, 1000);

	function randomColor(){
		console.log( "Random color called" );

		var r = Math.floor((Math.random() * 255) + 1);
		var g = Math.floor((Math.random() * 255) + 1);
		var b = Math.floor((Math.random() * 255) + 1);
		// Setting de color
		setRGB( r, g, b );

		$('a, .color, .color-bg').not('.main-nav a').css({
			WebkitTransition : 'all 5s linear',
			MozTransition    : 'all 5s linear',
			MsTransition     : 'all 5s linear',
			OTransition      : 'all 5s linear',
			transition       : 'all 5s linear'
		});

	}
		            
	function handleEvent(event) {
		moved = true;

		setInterval(function () {
			if (moved) {
				moved = false;

				// Para comparar si se ha movido en unos segundos
				if( !r_x_inicial && !r_y_inicial){		
					r_x_inicial = event.beta;
					r_y_inicial = event.gamma;
				}

				// valores crudos desde el acelerometro
				var r_x = event.beta;           
				var r_y = event.gamma; 
				var r_z = event.alpha; 

				r_x_actual = r_x;
				r_y_actual = r_y;

				handleAccelerometer( r_x, r_y, r_z );
			}

		}, 20);		
	}

	function handleAccelerometer ( r_x, r_y, r_z ){


		// convierte rangos de sensores para que todos vayan de 0 a 360 ver: http://w3c.github.io/deviceorientation/spec-source-orientation.html
		var x = r_x + 180;           
		var y = (r_y + 90) * 2;     
		var z = r_z;                

		// RGB
		var r = cicloCompleto( x, changeRangeTo255 ); 
		var g = cicloCompleto( y, changeRangeTo255 ); 
		var b = cicloCompleto( z, changeRangeTo255 ); 

		// HSL
		var h = cicloCompleto( x, Math.floor ); 
		var s = cicloCompleto( y, changeRangeTo100 ); 
		var l = cicloCompleto( z, changeRangeTo100 ); 

		// Setear colores
		setRGB( r, g, b );

		// HSL Descartado
		// $( 'body' ).css( 'background-color', 'hsl('+h+','+s+'%,'+l+'%)' );
		//window.console && console.info('Raw Position: x, y, z: ', x, y, z);
	}

	// Recibe RGB y setea los colores
	function setRGB( r, g, b ){
		// Evitamos el blanco 
		var componentes = [ r, g, b ];

		if( ( r + g + b ) > 700 ){
			// Escogemos un componente de color al azar y lo bajamos a 0 para que nunca haya blanco
			random_component = Math.floor(Math.random() * componentes.length);
			componentes[ random_component ] = componentes[ random_component ] - 80;
			console.log( "R", componentes[0] );
			console.log( "G", componentes[1] );
			console.log( "B", componentes[2] );
			console.log( "blanco!" );
		}

		// Setting de color
		$( 'a, .color' ).not('.main-nav a').css( 'color', 'rgb('+componentes[0]+','+componentes[1]+','+componentes[2]+')' );
		$( '.color-bg' ).css( 'background-color', 'rgb('+componentes[0]+','+componentes[1]+','+componentes[2]+')' );

	}

	// Evita saltos al pasar de 360 a 0 haciendo un ciclo completo de 0 a 360 y de vuelta
	function cicloCompleto( val, callback ){
		var converted = val;

		if( val > 180 ){
			converted = 360 - val;
		}
		return callback( converted );

	}

	// Porcentaje de un total
	function percentageOfTotal( val, total ){
		percentage = ( val * 100 ) / total;
		return Math.floor( percentage );
	}

	// Cambian rangos con regla de 3
	function changeRange100To255( val ){
		var converted = ( val * 255 ) / 100;
		return Math.floor( converted );
	}

	function changeRangeTo255( val ){
		var converted = ( val * 255 ) / 180;
		return Math.floor( converted );
	}

	function changeRangeTo100( val ){
		var converted = ( val * 100 ) / 180;
		return Math.floor( converted );
	}

	$(document).on( 'load' , randomColor() );

})(jQuery);
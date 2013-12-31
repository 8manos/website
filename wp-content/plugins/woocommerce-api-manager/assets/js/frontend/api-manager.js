jQuery(document).ready(function($) {
	$(document).on( 'click', '.plus, .minus', function() {

		// Get values
		var $qty 		= $(this).closest('.quantity').find(".qty");
	    var currentVal 	= parseFloat( $qty.val() );
	    var max 		= parseFloat( $qty.attr('max') );
	    var min 		= parseFloat( $qty.attr('min') );
	    var step 		= $qty.attr('step');

	    // Format values
	    if ( ! currentVal || currentVal == "" || currentVal == "NaN" ) currentVal = 0;
	    if ( max == "" || max == "NaN" ) max = '';
	    if ( min == "" || min == "NaN" ) min = 0;
	    if ( step == 'any' || step == "" || step == undefined || parseFloat( step ) == "NaN" ) step = 1;

	    // Change the value
	    if ( $(this).is('.plus') ) {

		    if ( max && ( max == currentVal || currentVal > max ) ) {
		    	$qty.val( max );
		    } else {
		    	$qty.val( currentVal + parseFloat( step ) );
		    }

	    } else {

		    if ( min && ( min==currentVal || currentVal < min ) ) {
		    	$qty.val( min );
		    } else if ( currentVal > 0 ) {
		    	$qty.val( currentVal - parseFloat( step ) );
		    }

	    }

	    // Trigger change event
	    $qty.trigger('change');
	});
});

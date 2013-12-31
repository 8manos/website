/**
 * Copyright: (c) 2011-2013 Todd Lahman LLC
 */
jQuery(document).ready(function($){

	$.extend({
		showHideVariableAPIMeta: function(){
			if ($('select#product-type').val()=='variable-subscription') {
				$('.show_if_variable').show();
				$('.show_if_variable-subscription').show();
				$('.hide_if_variable-subscription').hide();
			} else {
				$('.show_if_variable-subscription').hide();
				$('.hide_if_variable-subscription').show();
			}y
		},
	// When a variation is added
	$('#variable_product_options').on('woocommerce_variations_added',function(){
		$.showHideVariableAPIMeta();
	});

	if($('.options_group.pricing').length > 0) {
		$.showHideVariableAPIMeta();
	}

	$('body').bind('woocommerce-product-type-change',function(){
		$.showHideVariableAPIMeta();
	});

	$('input#_downloadable, input#_virtual').change(function(){
		$.showHideVariableAPIMeta();
	});

});
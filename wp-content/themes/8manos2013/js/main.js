(function($) {
	var items = $('.slides li').width();	  
	$('.gallery').flexslider({
		animation: "slide",
		slideshow: false,
		controlNav: false,
		directionNav: false,
		itemWidth: items,
		itemMargin: 8,
		minItems: 2,
		maxItems: 3
	});
	

})(jQuery);
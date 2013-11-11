(function($) {
	var items = $('.slides li').width();	  
	$('.gallery').flexslider({
		animation: 'slide',
		slideshow: false,
		controlNav: false,
		directionNav: false,
		itemWidth: items,
		itemMargin: 8,
		minItems: 2,
		maxItems: 3
	});
	
	
/////// imagenes equipo
	
	$('.thumbnail').each(function(){
	    var imageUrl = $(this).find('img').attr('src');
		$(this).css('background-image', 'url("' + imageUrl + '")');
	});

/////// mosaico laboratorio
	$('#mosaic').freetile({
		selector: 'article'
	});
	
/////// tabs
	$('.nav-tabs li:first').addClass('active').show();
	$('.content.tabs:first').show();

	$('.nav-tabs li').click(function() {
		$('.nav-tabs li').removeClass('active');
		$(this).addClass('active');
		$('.content.tabs').hide();
		var activeTab = $(this).find('a').attr('href');
		$(activeTab).fadeIn();
		return false;
	});
	
})(jQuery);
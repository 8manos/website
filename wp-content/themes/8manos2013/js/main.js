(function($) {
	var items = $('.slides li').width();	  
	$('.gallery').flexslider({
		animation: 'slide',
		slideshow: false,
		controlNav: false,
		directionNav: false,
		itemWidth: items,
		itemMargin: 8,
		minItems: 1,
		maxItems: 2
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

	$('.nav-tabs li').on('click', function() {
		$('.nav-tabs li').removeClass('active');
		$(this).addClass('active');
		$('.content.tabs').hide();
		var activeTab = $(this).find('a').attr('href');
		$(activeTab).fadeIn();
		//$('html, body').stop().animate({scrollTop: $(activeTab).offset().top}, 1000);
		return false;
	});
	
/////// form aplicar
	 $('.aplicar').on('click', function() {
		var scrollPoint = $(this).attr('href');
		$('.contact-frame').slideDown(800);
		$('html, body').stop().animate({scrollTop: $(scrollPoint).offset().top}, 1000);
		return false;
  	});
	
})(jQuery);
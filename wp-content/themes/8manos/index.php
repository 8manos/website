<?php get_header(); ?>

  <div role="main" id="contenidos" class="clearfix">

	<?php if(is_single() && get_post_type() == 'equipo'){
		get_template_part('loop','equipo'); 
	}else{
		get_template_part('loop'); 
	} ?>

  </div>

<?php get_footer(); ?>


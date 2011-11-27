<?php global $debug; 
	if(have_posts()): while(have_posts()): the_post();
				
	$col_izq = simple_fields_get_post_value(get_the_ID(), array(1,1), true);
	$url = simple_fields_get_post_value(get_the_ID(), array(2,1), true);

	$clases="";
	if($col_izq){
		$classes ="has_col_izq";		
	}
	
	$classes .= "clearfix ".$classes;
      ?>

        <article id="article-<?php the_ID(); ?>" <?php post_class($classes); ?>>

	<?php if(is_archive() && get_post_type() == 'equipo' || is_single() && get_post_type() == 'equipo'){ ?>
		<div class="foto_perfil">
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
			<?php the_post_thumbnail('thumb-equipo'); ?>
			</a>
		</div>
	  <?php } ?>

	<h2 class="entry-title" style="<?php el_estilo($url); ?>">
		<a href="<?php the_permalink(); ?>">
			<?php if($url && is_archive() && get_post_type() == 'portafolio'){ ?>
				<?php echo($url); ?>
			<?php }else{ ?>
				<?php the_title(); ?>
			<?php } ?>
		</a>

		<?php if($url && is_single() && get_post_type() == 'portafolio'){ ?>
			<a href="<?php echo(httpify($url)); ?>" class="visit_link" target="blank">VER SITIO ➚</a>
		<?php } ?>
	</h2>

	<?php if(is_archive() && get_post_type() == 'equipo' || is_single() && get_post_type() == 'equipo'){ 
		$terms = wp_get_object_terms(get_the_ID(), "especialidades");
		 $count = count($terms);
		 if ( $count > 0 ){
		     echo "<ul class='especialidades'>";
		     foreach ( $terms as $term ) {
		       echo "<li>" . $term->name . "</li>";
		
		     }
		     echo "</ul>";
		 }
	} ?>

	  <?php if(is_archive() && get_post_type() == 'portafolio'){ ?>
		<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
			<?php the_post_thumbnail('thumb-portafolio'); ?>
		</a>

	  <?php }else{ ?>

		<?php
			if(is_single() && get_post_type() == 'portafolio'){
				echo "<div class='portfolio_gallery'>";
					echo do_shortcode('[gallery]');
				echo "</div>";
			}
		?>

		<?php 
			if($col_izq){
			echo("<div class='col_izq'>");
				echo($col_izq);	
			echo('</div>');		
			}
		?>

		<?php if(!is_archive()){ ?>
		  <div class="post-content">
			<?php
				the_content();
			?>
		  </div>
		<?php } ?>

	  <?php } ?>

	<?php if($debug == true){ ?>
		<?php the_meta(); ?> 
	<?php } ?>
        </article>

      <?php unset($classes); endwhile; ?>
    <?php else : ?>
	<h2>No hay nada</h2>
    <?php endif; ?>

<?php global $debug; 
	if(have_posts()): while(have_posts()): the_post();
				
	$url = simple_fields_get_post_value(get_the_ID(), array(2,1), true);

	// Hoja de vida
	$experiencia = simple_fields_get_post_value(get_the_ID(), 'Experiencia', true);
	$educacion = simple_fields_get_post_value(get_the_ID(), 'Educación', true);
	$referencias = simple_fields_get_post_value(get_the_ID(), 'Referencias', true);
	$contacto = simple_fields_get_post_value(get_the_ID(), 'Contacto', true);
	$proyectos = simple_fields_get_post_group_values(get_the_id(),'Proyectos realizados', true, 2);

	$clases="";
	if($col_izq){
		$classes ="has_col_izq";		
	}
	
	$classes .= "clearfix ".$classes;
      ?>

        <article id="article-<?php the_ID(); ?>" <?php post_class($classes); ?>>

		<div class="foto_perfil">
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
			<?php the_post_thumbnail('thumb-equipo'); ?>
			</a>
		</div>

          <h2 class="entry-title" style="<?php el_estilo($url); ?>">
            <a href="<?php the_permalink(); ?>">
		<?php the_title(); ?>
	    </a>
          </h2>

	  <?php
		 $terms = wp_get_object_terms(get_the_ID(), "especialidades");
		 $count = count($terms);
		 if ( $count > 0 ){
		     echo "<ul class='especialidades'>";
		     foreach ( $terms as $term ) {
		       echo "<li>" . $term->name . "</li>";
		
		     }
		     echo "</ul>";
		 }

	  ?>

          <div class="post-content">

		<div id="tabs">
			<ul id="tabs_nav" class="clearfix">
				<li><a href="#presentacion-personal">Datos personales</a></li>

				<?php if($experiencia){ ?>
					<li><a href="#experiencia">Experiencia</a></li>
				<?php } ?>

				<?php if($educacion){ ?>
					<li><a href="#educacion">Educación</a></li>
				<?php } ?>

				<?php if($referencias){ ?>
					<li><a href="#referencias">Referencias</a></li>
				<?php } ?>

				<?php if($contacto){ ?>
					<li><a href="#contacto">Contacto</a></li>
				<?php } ?>

			</ul>
			<div id="presentacion-personal">
				<?php
					the_content();
				?> 

				<?php
					if($proyectos){
				?>
					<h3>Proyectos</h3>

				<dl>
				<?php
					foreach($proyectos as $proyecto){
				?>
					<dt>
					<?php if($proyecto['Enlace proyecto']){ ?>
						<a target="_blank" href="<?=$proyecto['Enlace proyecto']; ?>">
					<?php } ?>

						<?php echo($proyecto['Titulo proyecto']); ?>
					<?php if($proyecto['Enlace proyecto']){ ?>
						</a>
					<?php } ?>
					</dt>

					<dd>
						<?php echo($proyecto['Descripción proyecto']); ?>
					</dd>
				<?php

					}
						// print_r($proyectos);
					}
				?>
				</dl>
			</div>

			<?php if($experiencia){ ?>
				<div id="experiencia">
					<h3>Experiencia</h3>
					<?php echo($experiencia); ?>
				</div>
			<?php } ?>

			<?php if($educacion){ ?>
				<div id="educacion">
					<h3>Educación</h3>
					<?php echo($educacion); ?>
				</div>
			<?php } ?>

			<?php if($referencias){ ?>
				<div id="referencias">
					<h3>Referencias</h3>
					<?php echo($referencias); ?>
				</div>
			<?php } ?>

			<?php if($contacto){ ?>
				<div id="contacto">
					<h3>Contacto</h3>
					<?php echo($contacto); ?>
				</div>
			<?php } ?>

		</div>
          </div>

	<?php if($debug == true){ ?>
		<?php the_meta(); ?> 
	<?php } ?>
        </article>

      <?php unset($classes); endwhile; ?>
    <?php else : ?>
	<h2>No hay nada</h2>
    <?php endif; ?>

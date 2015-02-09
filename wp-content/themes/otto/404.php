<?php get_header(); ?>
<main role="main">
  <div class="inner-main">
    <h2>404: Parece que encontraste el escondite de Otto!</h2>
    <h3>Pero no deberías estar aquí. Vuelve a la <a href="<?php echo home_url(); ?>/">página de inicio</a> o contáctanos si crees que aquí debería haber algo <strong>&#x2193;</strong></h3>

    <div class="hidden-otto">
      <svg viewBox="0 0 128 56" class="icon shape-codepen">
        <use xlink:href="#otto"></use>
      </svg>
    </div>
  </div>
</main>
<?php get_footer(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php bloginfo('text_direction'); ?>" xml:lang="<?php bloginfo('language'); ?>">
<head>
    <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<title>
        <?php
            global $page, $paged;
            wp_title('|', true, 'right');
            bloginfo('name');
            $site_description = get_bloginfo('description', 'display');
            if ( $site_description && ( is_home() || is_front_page()))
                echo " | $site_description";
            if ($paged >= 2 || $page >= 2)
                echo ' | ' . sprintf( __('Page %s'), max($paged, $page));
        ?>
    </title>
    <meta http-equiv="Content-language" content="<?php bloginfo('language'); ?>" />
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="shortcut icon" href="<?php bloginfo('template_url'); ?>/images/favico.ico" type="image/x-icon" />
    <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_url'); ?>" />
    <!--[if IE]><link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('template_url'); ?>/ie.css" /><![endif]-->
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
    <link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
    <link rel="alternate" type="text/xml" title="RSS .92" href="<?php bloginfo('rss_url'); ?>"/>
    <link rel="alternate" type="application/atom+xml" title="Atom 0.3" href="<?php bloginfo('atom_url'); ?>" />
    <?php
        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'lazyload', get_template_directory_uri() . '/js/jquery.lazyload.mini.js', 'jquery', false );
        if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
        wp_enqueue_script( 'script', get_template_directory_uri() . '/js/script.js', 'jquery', false );
    ?>
    <?php wp_head(); ?>
</head>
<body>
	<div id="wrapper">
		<div class="header clear">


        		<?php         		
	        	ob_start();
				ob_implicit_flush(0);
				echo get_option('suburbia_custom_logo'); 
				$my_logo = ob_get_contents();
				ob_end_clean();
        		if (
		        $my_logo == ''
        		): ?>
        		<a href="<?php bloginfo("url"); ?>/">
				<img src="<?php bloginfo('template_url'); ?>/images/logo.png" alt="<?php bloginfo('name'); ?>" /></a>
        		<?php else: ?>
        		<a href="<?php bloginfo("url"); ?>/"><img src="<?php echo get_option('suburbia_custom_logo'); ?>" alt="<?php bloginfo('name'); ?>" /></a>        		
        		<?php endif ?>			
			
			
			
            <div class="desc">
            	<?php if (is_page()) { ?>


		    	        <?php         		
			        	ob_start();
						ob_implicit_flush(0);
						echo get_option('suburbia_apropos'); 
						$my_logo = ob_get_contents();
						ob_end_clean();
		        		if (
				        $my_logo == ''
		        		): ?>
		        		<a href="<?php bloginfo("url"); ?>/">
						<img src="<?php bloginfo('template_url'); ?>/images/logo2.gif" class="dem-home" alt="<?php bloginfo('name'); ?>" width="155" height="155" /></a>
		        		<?php else: ?>
		        		<a href="<?php bloginfo("url"); ?>/"><img src="<?php echo get_option('suburbia_apropos'); ?>" class="dem-home" width="155" height="155" /></a>       		
		        		<?php endif ?>


				<?php } else if (is_home()) { ?>
				<p><?php bloginfo('description'); ?></p>
				<?php } else { ?>
				<?php } ?>
                
                <?php if ( is_404() ) :?>
                    <p class="err404">404 Page not found</p>
                <?php endif; ?>
            </div>
            <div class="space">
               	<?php if (is_single()) { ?>
            	<p><?php $turl = getTinyUrl(get_permalink($post->ID)); echo '<a class="stiff" href="'.$turl.'" >'.$turl.'</a>' ?></p>
                <?php } elseif (is_page()) { ?>
            	<p><?php $turl = getTinyUrl(get_permalink($post->ID)); echo '<a class="stiff" href="'.$turl.'" >'.$turl.'</a>' ?></p>
            	<?php } else { ?>


		    	        <?php         		
			        	ob_start();
						ob_implicit_flush(0);
						echo get_option('suburbia_apropos'); 
						$my_logo = ob_get_contents();
						ob_end_clean();
		        		if (
				        $my_logo == ''
		        		): ?>
		        		<a href="<?php bloginfo("url"); ?>/">
						<img src="<?php bloginfo('template_url'); ?>/images/logo2.gif" class="dem-home" alt="<?php bloginfo('name'); ?>" width="155" height="155" /></a>
		        		<?php else: ?>
		        		<a href="<?php bloginfo("url"); ?>/"><img src="<?php echo get_option('suburbia_apropos'); ?>" class="dem-home" width="155" height="155" /></a>       		
		        		<?php endif ?>


				<?php } ?>
			</div>
		</div>
        <div class="middle clear">

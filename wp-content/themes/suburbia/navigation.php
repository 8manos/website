<div class="aside"<?php echo (is_category() || is_tag())? 'style="border-bottom: 1px solid #e0e0e0 !important; border-top: none !important;"' : ''; ?>>
	<?php if (is_tag()) { ?>
	    <h3>Posts Tagged "<?php single_tag_title(); ?>"</h3> 
		<p>Check out all of the posts tagged with "<?php single_tag_title(); ?>".</p>
	<?php } elseif (is_search()) { ?>
		<h3>You are searching for:</h3> 
    	<p><?php echo wp_specialchars(stripslashes($_GET['s']), true); ?></p>

	<?php } elseif (is_category()) { ?>
		<h3>
        <?php wp_title(' ') //remove the space to revert back to &raquo; - http://codex.wordpress.org/Function_Reference/wp_title ?>
        </h3>		    

	<?php } else { ?>
	<?php } ?>    	
        <?php if (is_category('')) { ?>
            <p><?php custom_excerpt(category_description(), 60); ?></p>
        <?php } ?>
</div>

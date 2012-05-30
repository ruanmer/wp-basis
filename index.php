<?php
/* Index Template */
get_header();
    
    the_post(); 
?>
	<div id="content">
		<?php get_template_part( 'content', 'default' ); ?>
	</div><!--/#content-->
	
<?php get_footer(); ?>